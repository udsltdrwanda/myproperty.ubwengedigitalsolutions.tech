<?php

namespace App\Livewire\User\LandLord\Crm;

use App\Models\House;
use App\Models\Invoice;
use App\Models\RentRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CrmAnalyticsLivewire extends Component
{
    // ── Filters ──────────────────────────────────────────
    public string $search           = '';
    public string $invoiceStatus    = '';   // Paid|Partial|Pending|Canceled
    public string $contractStatus   = '';   // active|expired
    public string $paymentRate      = '';   // high|medium|low
    public string $houseFilter      = '';   // house id
    public string $year             = '';   // '' = all years
    public string $sortBy           = 'total_billed'; // total_billed|payment_rate|name|contracts
    public string $sortDir          = 'desc';

    // ── Data ─────────────────────────────────────────────
    public $houses = [];

    public function mount()
    {
        $landlordId = Auth::user()->landlord_id;

        // Get house IDs that appear in this landlord's rent records
        $houseIds = RentRecord::where('landlord_id', $landlordId)
            ->with('unit')
            ->get()
            ->pluck('unit.house_id')
            ->filter()
            ->unique()
            ->values();

        $this->houses = House::whereIn('id', $houseIds)->orderBy('name')->get();
    }

    public function updatedSearch()      { }
    public function updatedHouseFilter() { }

    public function toggleSort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'desc';
        }
    }

    public function resetFilters(): void
    {
        $this->search         = '';
        $this->invoiceStatus  = '';
        $this->contractStatus = '';
        $this->paymentRate    = '';
        $this->houseFilter    = '';
        $this->year           = '';
        $this->sortBy         = 'total_billed';
        $this->sortDir        = 'desc';
    }

    private function yearRange(): ?array
    {
        if ($this->year === '' || $this->year === null) {
            return null;
        }

        $year = (int) $this->year;

        return [
            Carbon::createFromDate($year, 1, 1)->startOfYear()->toDateString(),
            Carbon::createFromDate($year, 12, 31)->endOfYear()->toDateString(),
        ];
    }

    private function applyYearToInvoices($query)
    {
        $range = $this->yearRange();
        if (!$range) {
            return $query;
        }

        [$start, $end] = $range;

        return $query->where(function ($q) use ($start, $end) {
            $q->where(function ($dates) use ($start, $end) {
                $dates->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start);
            })->orWhereHas('rentrecord', function ($contract) use ($start, $end) {
                $contract->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start);
            });
        });
    }

    private function applyYearToContracts($query)
    {
        $range = $this->yearRange();
        if (!$range) {
            return $query;
        }

        [$start, $end] = $range;

        return $query->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start);
    }

    private function availableYears(): array
    {
        $landlordId = Auth::user()->landlord_id;
        $current = (int) now()->year;

        $fromInvoices = Invoice::query()
            ->where('landlord_id', $landlordId)
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as y')
            ->distinct()
            ->pluck('y');

        $fromContracts = RentRecord::query()
            ->where('landlord_id', $landlordId)
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as y')
            ->distinct()
            ->pluck('y');

        return $fromInvoices
            ->merge($fromContracts)
            ->map(fn ($year) => (int) $year)
            ->filter(fn ($year) => $year >= 2000 && $year <= $current + 1)
            ->merge(range($current, $current - 5))
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }

    public function render()
    {
        $landlordId = Auth::user()->landlord_id;

        // ── Base queries ──────────────────────────────────
        $rentQuery = RentRecord::with(['tenant', 'unit.house', 'invoices.payments'])
            ->where('landlord_id', $landlordId);
        $this->applyYearToContracts($rentQuery);

        if ($this->houseFilter) {
            $rentQuery->whereHas('unit', fn($q) => $q->where('house_id', $this->houseFilter));
        }

        $rentRecords = $rentQuery->latest()->get();

        $invoiceQuery = Invoice::with(['tenant', 'unit.house', 'payments'])
            ->where('landlord_id', $landlordId);
        $this->applyYearToInvoices($invoiceQuery);

        if ($this->houseFilter) {
            $invoiceQuery->whereHas('unit', fn($q) => $q->where('house_id', $this->houseFilter));
        }

        if ($this->invoiceStatus) {
            $invoiceQuery->where('invoice_status', $this->invoiceStatus);
        }

        $allInvoices = $invoiceQuery->latest()->get();

        // ── Per-tenant profiles ───────────────────────────
        $tenants = $rentRecords->pluck('tenant')
            ->merge($allInvoices->pluck('tenant'))
            ->filter()
            ->unique('id')
            ->values();

        $profiles = $tenants->map(function ($tenant) use ($rentRecords, $allInvoices) {
            $recs  = $rentRecords->where('tenant_id', $tenant->id);
            $invs  = $allInvoices->where('tenant_id', $tenant->id);

            $billed  = $invs->sum(fn($i) => floatval($i->amount) + floatval($i->vat));
            $paid    = $invs->flatMap->payments->sum('payed_amount');
            $balance = $billed - $paid;
            $rate    = $billed > 0 ? round(($paid / $billed) * 100) : 0;

            $activeCount = $recs->filter(fn($r) => Carbon::parse($r->end_date)->isFuture())->count();
            $latestRec   = $recs->sortByDesc('end_date')->first();

            return [
                'tenant'           => $tenant,
                'contracts'        => $recs->count(),
                'active_contracts' => $activeCount,
                'invoices_count'   => $invs->count(),
                'total_billed'     => $billed,
                'total_paid'       => $paid,
                'total_balance'    => $balance,
                'invoice_statuses' => $invs->groupBy('invoice_status')->map->count(),
                'latest_record'    => $latestRec,
                'payment_rate'     => $rate,
            ];
        });

        // ── Client-level filters ──────────────────────────
        if ($this->search !== '') {
            $s = strtolower($this->search);
            $profiles = $profiles->filter(function ($p) use ($s) {
                $t = $p['tenant'];
                return str_contains(strtolower($t->tenant_name ?? ''), $s)
                    || str_contains(strtolower($t->email ?? ''), $s)
                    || str_contains(strtolower($t->phone ?? ''), $s)
                    || str_contains(strtolower($t->company_name ?? ''), $s);
            });
        }

        if ($this->contractStatus === 'active') {
            $profiles = $profiles->filter(fn($p) => $p['active_contracts'] > 0);
        } elseif ($this->contractStatus === 'expired') {
            $profiles = $profiles->filter(fn($p) => $p['active_contracts'] === 0);
        }

        if ($this->paymentRate === 'high') {
            $profiles = $profiles->filter(fn($p) => $p['payment_rate'] >= 80);
        } elseif ($this->paymentRate === 'medium') {
            $profiles = $profiles->filter(fn($p) => $p['payment_rate'] >= 50 && $p['payment_rate'] < 80);
        } elseif ($this->paymentRate === 'low') {
            $profiles = $profiles->filter(fn($p) => $p['payment_rate'] < 50);
        }

        // ── Sort ──────────────────────────────────────────
        $sorted = match ($this->sortBy) {
            'payment_rate' => $profiles->sortBy('payment_rate'),
            'name'         => $profiles->sortBy(fn($p) => strtolower($p['tenant']->tenant_name)),
            'contracts'    => $profiles->sortBy('contracts'),
            default        => $profiles->sortBy('total_billed'),
        };

        $tenantProfiles = $this->sortDir === 'desc' ? $sorted->reverse()->values() : $sorted->values();

        // ── KPIs (respect year filter, ignore other client filters) ──
        $kpiInvoiceQuery = Invoice::with('payments')->where('landlord_id', $landlordId);
        $this->applyYearToInvoices($kpiInvoiceQuery);
        $allLandlordInvoices = $kpiInvoiceQuery->get();
        $totalRevenue        = $allLandlordInvoices->sum(fn($i) => floatval($i->amount) + floatval($i->vat));
        $totalCollected      = $allLandlordInvoices->flatMap->payments->sum('payed_amount');
        $totalOutstanding    = $totalRevenue - $totalCollected;

        $kpiRentQuery = RentRecord::where('landlord_id', $landlordId);
        $this->applyYearToContracts($kpiRentQuery);
        $allRentRecords = $kpiRentQuery->get();
        $activeTenantsCount = $allRentRecords
            ->filter(fn($r) => Carbon::parse($r->end_date)->isFuture())
            ->pluck('tenant_id')->unique()->count();

        return view('livewire.user.land-lord.crm.crm-analytics-livewire', [
            'tenantProfiles' => $tenantProfiles,
            'totalRevenue' => $totalRevenue,
            'totalCollected' => $totalCollected,
            'totalOutstanding' => $totalOutstanding,
            'activeTenantsCount' => $activeTenantsCount,
            'availableYears' => $this->availableYears(),
        ]);
    }
}
