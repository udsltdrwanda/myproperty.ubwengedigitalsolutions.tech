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
        $this->sortBy         = 'total_billed';
        $this->sortDir        = 'desc';
    }

    public function render()
    {
        $landlordId = Auth::user()->landlord_id;

        // ── Base queries ──────────────────────────────────
        $rentQuery = RentRecord::with(['tenant', 'unit.house', 'invoices.payments'])
            ->where('landlord_id', $landlordId);

        if ($this->houseFilter) {
            $rentQuery->whereHas('unit', fn($q) => $q->where('house_id', $this->houseFilter));
        }

        $rentRecords = $rentQuery->latest()->get();

        $invoiceQuery = Invoice::with(['tenant', 'unit.house', 'payments'])
            ->where('landlord_id', $landlordId);

        if ($this->houseFilter) {
            $invoiceQuery->whereHas('unit', fn($q) => $q->where('house_id', $this->houseFilter));
        }

        if ($this->invoiceStatus) {
            $invoiceQuery->where('invoice_status', $this->invoiceStatus);
        }

        $allInvoices = $invoiceQuery->latest()->get();

        // ── Per-tenant profiles ───────────────────────────
        $tenants = $rentRecords->pluck('tenant')->filter()->unique('id')->values();

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

        // ── KPIs (use all invoices regardless of client filters) ──
        $allLandlordInvoices = Invoice::with('payments')->where('landlord_id', $landlordId)->get();
        $totalRevenue        = $allLandlordInvoices->sum(fn($i) => floatval($i->amount) + floatval($i->vat));
        $totalCollected      = $allLandlordInvoices->flatMap->payments->sum('payed_amount');
        $totalOutstanding    = $totalRevenue - $totalCollected;

        $allRentRecords = RentRecord::where('landlord_id', $landlordId)->get();
        $activeTenantsCount = $allRentRecords
            ->filter(fn($r) => Carbon::parse($r->end_date)->isFuture())
            ->pluck('tenant_id')->unique()->count();

        return view('livewire.user.land-lord.crm.crm-analytics-livewire', compact(
            'tenantProfiles',
            'totalRevenue',
            'totalCollected',
            'totalOutstanding',
            'activeTenantsCount',
        ));
    }
}
