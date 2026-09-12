<?php

namespace App\Http\Controllers\User\landlord;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\HouseAdjacement;
use App\Models\Invoice;
use App\Models\LandAdjacement;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RentRecord;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class LandlordDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $landlordId = $user->landlord_id;
        $year = (int) now()->year;
        $yearStart = now()->startOfYear()->toDateString();
        $yearEnd = now()->endOfYear()->toDateString();

        $properties = Property::where('landlord_id', $landlordId)->with('districtRelation')->get();
        $totalProperties = $properties->count();
        $totalHouses = House::whereHas('property', fn ($q) => $q->where('landlord_id', $landlordId))->count();

        $units = PropertyUnit::whereHas('house.property', fn ($q) => $q->where('landlord_id', $landlordId))
            ->with('activeRentRecord')
            ->get();
        $totalUnits = $units->count();
        $occupiedUnits = $units->filter(fn ($unit) => $unit->activeRentRecord)->count();
        $maintenanceUnits = $units->where('unit_status', 'maintenance')->count();
        $vacantUnits = max(0, $totalUnits - $occupiedUnits);
        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;

        $totalTenants = Tenant::where('landlord_id', $landlordId)->count();
        $activeContracts = RentRecord::where('landlord_id', $landlordId)
            ->whereDate('end_date', '>=', now())
            ->count();
        $activeTenants = RentRecord::where('landlord_id', $landlordId)
            ->whereDate('end_date', '>=', now())
            ->pluck('tenant_id')
            ->filter()
            ->unique()
            ->count();
        $expiringContracts = RentRecord::with(['tenant', 'unit.house'])
            ->where('landlord_id', $landlordId)
            ->whereDate('end_date', '>=', now())
            ->whereDate('end_date', '<=', now()->addDays(60))
            ->orderBy('end_date')
            ->limit(5)
            ->get();

        $yearInvoices = $this->yearInvoices($landlordId, $yearStart, $yearEnd);
        $openInvoices = $yearInvoices->where('invoice_status', '!=', 'Canceled');

        $rentAmount = (float) $openInvoices->sum('amount');
        $vatAmount = (float) $openInvoices->sum('vat');
        $totalBilled = $rentAmount + $vatAmount;
        $collectedAmount = (float) $openInvoices->flatMap->payments->sum('payed_amount');
        $outstandingAmount = max(0, $totalBilled - $collectedAmount);
        $collectionRate = $totalBilled > 0 ? round(($collectedAmount / $totalBilled) * 100, 1) : 0;

        $paidInvoices = $yearInvoices->where('invoice_status', 'Paid')->count();
        $pendingInvoices = $yearInvoices->where('invoice_status', 'Pending')->count();
        $partialInvoices = $yearInvoices->where('invoice_status', 'Partial')->count();
        $canceledInvoices = $yearInvoices->where('invoice_status', 'Canceled')->count();
        $totalInvoices = $yearInvoices->count();

        $paidWithEbm = $yearInvoices->where('invoice_status', 'Paid')->filter(fn ($invoice) => $invoice->ebmInvoice)->count();
        $paidWithoutEbm = $yearInvoices->where('invoice_status', 'Paid')->filter(fn ($invoice) => ! $invoice->ebmInvoice)->count();

        $rentalTaxEstimate = $this->rentalTaxEstimate($openInvoices);
        $propertyTax = $this->propertyTaxForYear($properties, $landlordId, $year);

        $recentInvoices = Invoice::with(['property', 'tenant'])
            ->where(function ($query) use ($landlordId) {
                $query->where('landlord_id', $landlordId)
                    ->orWhereHas('property', fn ($property) => $property->where('landlord_id', $landlordId));
            })
            ->latest()
            ->limit(6)
            ->get();

        $monthlyTrend = $this->monthlyTrend($landlordId);

        return view('user.landlord.dashboard', compact(
            'user',
            'year',
            'totalProperties',
            'totalHouses',
            'totalUnits',
            'occupiedUnits',
            'vacantUnits',
            'maintenanceUnits',
            'occupancyRate',
            'totalTenants',
            'activeTenants',
            'activeContracts',
            'expiringContracts',
            'rentAmount',
            'vatAmount',
            'totalBilled',
            'collectedAmount',
            'outstandingAmount',
            'collectionRate',
            'paidInvoices',
            'pendingInvoices',
            'partialInvoices',
            'canceledInvoices',
            'totalInvoices',
            'paidWithEbm',
            'paidWithoutEbm',
            'rentalTaxEstimate',
            'propertyTax',
            'recentInvoices',
            'monthlyTrend'
        ));
    }

    private function yearInvoices(string $landlordId, string $yearStart, string $yearEnd)
    {
        return Invoice::with(['payments', 'ebmInvoice', 'property.districtRelation'])
            ->where(function ($query) use ($landlordId) {
                $query->where('landlord_id', $landlordId)
                    ->orWhereHas('property', fn ($property) => $property->where('landlord_id', $landlordId));
            })
            ->where(function ($query) use ($yearStart, $yearEnd) {
                $query->where(function ($dates) use ($yearStart, $yearEnd) {
                    $dates->whereDate('start_date', '<=', $yearEnd)
                        ->whereDate('end_date', '>=', $yearStart);
                })->orWhereHas('rentrecord', function ($contract) use ($yearStart, $yearEnd) {
                    $contract->whereDate('start_date', '<=', $yearEnd)
                        ->whereDate('end_date', '>=', $yearStart);
                });
            })
            ->get();
    }

    private function rentalTaxEstimate($invoices): float
    {
        return (float) $invoices
            ->groupBy(fn ($invoice) => optional(optional($invoice->property)->districtRelation)->id ?? 'unknown')
            ->sum(function ($group) {
                $taxable = ((float) $group->sum('amount')) / 2;
                if ($taxable <= 180000) {
                    return 0;
                }
                if ($taxable <= 1000000) {
                    return ($taxable - 180000) * 0.2;
                }

                return 820000 * 0.2 + ($taxable - 1000000) * 0.3;
            });
    }

    private function propertyTaxForYear($properties, string $landlordId, int $year): float
    {
        $total = 0;

        foreach ($properties as $property) {
            $land = LandAdjacement::where('property_id', $property->id)
                ->where('landlord_id', $landlordId)
                ->where('year', $year)
                ->first();

            $total += ((float) ($land->value_per_m ?? 0)) * ((float) ($property->area ?? 0));

            $houseIds = House::where('property_id', $property->id)->pluck('id');
            $total += HouseAdjacement::whereIn('house_id', $houseIds)
                ->where('landlord_id', $landlordId)
                ->where('year', $year)
                ->get()
                ->sum(fn ($house) => ((float) ($house->house_value ?? 0)) * ((float) ($house->tax_rate ?? 0)));
        }

        return (float) $total;
    }

    private function monthlyTrend(string $landlordId): array
    {
        $labels = [];
        $billed = [];
        $collected = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->copy()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $labels[] = $month->format('M Y');

            $invoices = Invoice::with('payments')
                ->where(function ($query) use ($landlordId) {
                    $query->where('landlord_id', $landlordId)
                        ->orWhereHas('property', fn ($property) => $property->where('landlord_id', $landlordId));
                })
                ->where('invoice_status', '!=', 'Canceled')
                ->whereDate('start_date', '<=', $end)
                ->whereDate('end_date', '>=', $start)
                ->get();

            $billed[] = round((float) $invoices->sum(fn ($invoice) => (float) $invoice->amount + (float) $invoice->vat), 0);
            $collected[] = round((float) $invoices->flatMap->payments->sum('payed_amount'), 0);
        }

        return compact('labels', 'billed', 'collected');
    }
}
