<?php

namespace App\Livewire\User\LandLord\RentalIncomeTax;

use App\Models\District;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\House;
use App\Models\RentRecord;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RentalIncomeTaxLivewire extends Component
{
    use WithPagination;

    public $isExporting = false;
    public $year;
    public $startDate = null;
    public $endDate = null;
    public $Total = 0;

    public function mount()
    {
        $this->year = (int) now()->year;
        $this->applyYearRange();
    }

    public function updatedYear($value): void
    {
        $this->year = (int) $value;
        $this->applyYearRange();
        $this->resetPage();
    }

    private function applyYearRange(): void
    {
        $year = (int) $this->year;
        $this->startDate = now()->setYear($year)->startOfYear()->format('Y-m-d');
        $this->endDate = now()->setYear($year)->endOfYear()->format('Y-m-d');
    }

    private function availableYears(): array
    {
        $current = (int) now()->year;

        $fromInvoices = Invoice::query()
            ->where('landlord_id', Auth::user()->landlord_id)
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as y')
            ->distinct()
            ->pluck('y');

        $fromContracts = RentRecord::query()
            ->where('landlord_id', Auth::user()->landlord_id)
            ->whereNotNull('start_date')
            ->selectRaw('YEAR(start_date) as y')
            ->distinct()
            ->pluck('y');

        $fromInvoices = $fromInvoices
            ->merge($fromContracts)
            ->map(fn ($year) => (int) $year)
            ->filter(fn ($year) => $year >= 2000 && $year <= $current + 1);

        return $fromInvoices
            ->merge(range($current, $current - 5))
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }

    private function calculateDistrictTax($annualIncome)
    {
        // This is exactly how Excel would calculate it
        if ($annualIncome <= 180000) {
            return 0;
        } elseif ($annualIncome <= 1000000) {
            return ($annualIncome - 180000) * 0.2;
        } else {
            return 820000 * 0.2 + ($annualIncome - 1000000) * 0.3;
        }
    }

    private function getTaxBreakdown($annualIncome)
    {
        $breakdown = [
            'first_bracket' => [
                'amount' => min($annualIncome, 180000),
                'rate' => '0%',
                'tax' => 0,
            ],
            'middle_bracket' => [
                'amount' => 0,
                'rate' => '20%',
                'tax' => 0,
            ],
            'upper_bracket' => [
                'amount' => 0,
                'rate' => '30%',
                'tax' => 0,
            ],
        ];

        if ($annualIncome > 180000) {
            $breakdown['middle_bracket']['amount'] = min($annualIncome - 180000, 820000);
            $breakdown['middle_bracket']['tax'] = $breakdown['middle_bracket']['amount'] * 0.2;
        }

        if ($annualIncome > 1000000) {
            $breakdown['upper_bracket']['amount'] = $annualIncome - 1000000;
            $breakdown['upper_bracket']['tax'] = $breakdown['upper_bracket']['amount'] * 0.3;
        }

        $breakdown['total_tax'] = $breakdown['first_bracket']['tax'] + $breakdown['middle_bracket']['tax'] + $breakdown['upper_bracket']['tax'];

        return $breakdown;
    }

    private function invoiceAmountForHouse($house, $property): float
    {
        $landlordId = Auth::user()->landlord_id;
        $unitIds = DB::table('property_units')->where('house_id', $house->id)->pluck('id');

        return (float) Invoice::query()
            ->where(function ($query) use ($landlordId, $property) {
                $query->where('landlord_id', $landlordId)
                    ->orWhere('property_id', $property->id);
            })
            ->where(function ($query) use ($unitIds, $property) {
                $query->whereIn('unit_id', $unitIds)
                    ->orWhere('property_id', $property->id);
            })
            ->where(function ($query) {
                $query->where(function ($dates) {
                    $dates->whereDate('start_date', '<=', $this->endDate)
                        ->whereDate('end_date', '>=', $this->startDate);
                })->orWhereHas('rentrecord', function ($contract) {
                    $contract->whereDate('start_date', '<=', $this->endDate)
                        ->whereDate('end_date', '>=', $this->startDate);
                });
            })
            ->sum('amount');
    }

    public function getDistrictHouseInvoiceData()
    {
        $landlordId = Auth::user()->landlord_id;
        $districts = District::with(['properties' => function ($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        }])->get();

        $districtsWithInvoices = $districts->map(function ($district) use ($landlordId) {
            $housesWithInvoices = collect();
            $districtTotal = 0;
            $Total = 0;
            $totalBankInterest = 0;

            foreach ($district->properties as $property) {
                // Add bank interest from property
                $totalBankInterest += $property->bankInterest ?? 0;

                $houses = House::where('property_id', $property->id)
                    ->whereHas('property', fn ($query) => $query->where('landlord_id', $landlordId))
                    ->withCount('units')
                    ->get();

                foreach ($houses as $house) {
                    $invoiceAmount = $this->invoiceAmountForHouse($house, $property);
                    $housesWithInvoices->push([
                        'house' => $house,
                        'property_name' => $property->name,
                        'property_upi' => $property->upi,
                        'invoice_amount' => $invoiceAmount,
                        'units_count' => $house->units_count,
                        'bank_interest' => $property->bankInterest ?? 0,
                    ]);
                    $Total += $invoiceAmount;
                    $districtTotal = $Total;
                }
            }

            // Calculate tax breakdown at the district level based on 50% of income minus bank interest
            $taxableIncome = $districtTotal / 2 - $totalBankInterest; // Apply 50% taxable rule and subtract bank interest
            $taxBreakdown = $this->getTaxBreakdown($taxableIncome);
            $districtTax = $taxBreakdown['total_tax'];

            return [
                'district_id' => $district->id,
                'district_name' => $district->name,
                'houses' => $housesWithInvoices,
                'total_amount' => $districtTotal,
                'bank_interest' => $totalBankInterest,
                'taxable_amount' => $taxableIncome, // 50% of total amount minus bank interest
                'total_tax' => $districtTax,
                'tax_breakdown' => $taxBreakdown,
                'annual_income' => $districtTotal,
            ];
        });

        $this->Total = $districtsWithInvoices->sum('total_amount');

        // Filter out districts with no houses or invoices
        return $districtsWithInvoices->filter(function ($district) {
            return $district['houses']->isNotEmpty();
        });
    }

    public function exportToCsv()
    {
        $this->isExporting = true;

        $fileName = 'annual_rental_income_summary_report_and_tax_by_district_' . $this->year . '_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add title row
            fputcsv($file, ['ANNUALLY RENTAL INCOME SUMMARY REPORT AND TAX BY DISTRICT']);
            fputcsv($file, ['Reporting Period: Jan 1 - Dec 31, ' . $this->year]);
            fputcsv($file, []); // Empty row for spacing

            // Add header row
            fputcsv($file, ['District', 'Total Annual Rental Income', '50% Taxable Rental Income', 'Bank Interest on Loan', 'Exempted Amount (0%)', 'Middle Bracket (20%)', 'Upper Bracket (30%)', 'Tax at 0%', 'Tax at 20%', 'Tax at 30%', 'Total Annual Tax']);

            $districts = $this->getDistrictHouseInvoiceData();
            foreach ($districts as $district) {
                $taxBreakdown = $district['tax_breakdown'];
                $totalAnnualIncome = $district['annual_income']; // Full income (100%)
                $taxableIncome = $totalAnnualIncome / 2; // 50% taxable rental income

                fputcsv($file, [
                    $district['district_name'],
                    number_format($totalAnnualIncome, 2) . ' FRW', // Match view formatting with 2 decimal places and FRW
                    number_format($taxableIncome, 2) . ' FRW', // 50% taxable rental income
                    number_format($district['bank_interest'], 2) . ' FRW', // Bank interest
                    number_format($taxBreakdown['first_bracket']['amount'], 2) . ' FRW', // Exempted Amount (0% bracket)
                    number_format($taxBreakdown['middle_bracket']['amount'], 2) . ' FRW', // Middle bracket amount (20%)
                    number_format($taxBreakdown['upper_bracket']['amount'], 2) . ' FRW', // Upper bracket amount (30%)
                    number_format($taxBreakdown['first_bracket']['tax'], 2) . ' FRW', // Tax at 0% (always 0)
                    number_format($taxBreakdown['middle_bracket']['tax'], 2) . ' FRW', // Tax at 20%
                    number_format($taxBreakdown['upper_bracket']['tax'], 2) . ' FRW', // Tax at 30%
                    number_format($district['total_tax'], 2) . ' FRW', // Total Annual Tax
                ]);
            }

            // Add grand totals
            $districts = $districts->filter(function ($district) {
                return $district['houses']->isNotEmpty();
            });

            fputcsv($file, []);
            fputcsv($file, [
                'GRAND TOTALS',
                number_format($this->Total, 2) . ' FRW', // Total annual rental income
                number_format($this->Total / 2, 2) . ' FRW', // 50% taxable rental income
                number_format($districts->sum('bank_interest'), 2) . ' FRW', // Total bank interest
                '', // No total for exempted amount
                '', // No total for middle bracket
                '', // No total for upper bracket
                '', // No total for tax at 0%
                '', // No total for tax at 20%
                '', // No total for tax at 30%
                number_format($districts->sum('total_tax'), 2) . ' FRW', // Grand total tax
            ]);

            fclose($file);
        };

        $this->isExporting = false;
        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $districtsWithInvoices = $this->getDistrictHouseInvoiceData();

        // Calculate grand totals
        $grandTotalIncome = $districtsWithInvoices->sum('total_amount');
        $grandTotalTax = $districtsWithInvoices->sum('total_tax');

        return view('livewire.user.land-lord.rental-income-tax.rental-income-tax-livewire', [
            'districtsWithInvoices' => $districtsWithInvoices,
            'grandTotalIncome' => $grandTotalIncome,
            'grandTotalTax' => $grandTotalTax,
            'availableYears' => $this->availableYears(),
        ]);
    }
}
