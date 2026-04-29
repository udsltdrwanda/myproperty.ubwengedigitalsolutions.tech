<?php

namespace App\Livewire\User\LandLord\RentalIncomeTax;

use App\Models\District;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\House;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RentalIncomeTaxLivewire extends Component
{
    use WithPagination;

    public $isExporting = false;
    public $startDate = null;
    public $endDate = null;
    public $Total = 0;

    public function mount()
    {
        // Year-to-date range for the report
        $this->startDate = now()->startOfYear()->format('Y-m-d');
        $this->endDate = now()->endOfYear()->format('Y-m-d');
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

    public function getDistrictHouseInvoiceData()
    {
        $districts = District::with(['properties'])->get();

        $districtsWithInvoices = $districts->map(function ($district) {
            $housesWithInvoices = collect();
            $districtTotal = 0;
            $Total = 0;
            $totalBankInterest = 0;

            foreach ($district->properties as $property) {
                // Add bank interest from property
                $totalBankInterest += $property->bankInterest ?? 0;

                $houses = House::where('property_id', $property->id)
                    ->join('properties', 'houses.property_id', '=', 'properties.id')
                    ->where('properties.landlord_id', Auth::user()->landlord_id)
                    ->withCount('units')
                    ->select('houses.*')
                    ->get();

                foreach ($houses as $house) {
                    // Calculate total invoice amount for this house
                    $invoiceAmount = DB::table('invoices')
                        ->join('property_units', 'invoices.unit_id', '=', 'property_units.id')
                        ->join('houses', 'property_units.house_id', '=', 'houses.id')
                        ->where('houses.id', $house->id)
                        ->where('invoices.landlord_id', Auth::user()->landlord_id)
                        ->whereDate('invoices.start_date', '>=', $this->startDate)
                        ->whereDate('invoices.end_date', '<=', $this->endDate)
                        ->sum('invoices.amount');
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

        $fileName = 'annual_rental_income_summary_report_and_tax_by_district_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add title row
            fputcsv($file, ['ANNUALLY RENTAL INCOME SUMMARY REPORT AND TAX BY DISTRICT']);
            fputcsv($file, ['Reporting Period: Jan 1 - Dec 31, ' . now()->format('Y')]);
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
        ]);
    }
}
