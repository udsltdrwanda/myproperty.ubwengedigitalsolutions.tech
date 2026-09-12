<?php

namespace App\Livewire\User\LandLord\Vat;

use App\Models\Invoice;
use App\Models\RentRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VatLivewire extends Component
{
    public $year;
    public $startDate = null;
    public $endDate = null;
    public $isExporting = false;

    public function mount()
    {
        $this->year = (int) now()->year;
        $this->applyYearRange();
    }

    public function updatedYear($value): void
    {
        $this->year = (int) $value;
        $this->applyYearRange();
    }

    private function applyYearRange(): void
    {
        $year = (int) $this->year;
        $this->startDate = now()->setYear($year)->startOfYear()->format('Y-m-d');
        $this->endDate = now()->setYear($year)->endOfYear()->format('Y-m-d');
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

    private function yearInvoices()
    {
        $landlordId = Auth::user()->landlord_id;

        return Invoice::query()
            ->with(['property.districtRelation', 'unit.house', 'tenant'])
            ->where(function ($query) use ($landlordId) {
                $query->where('landlord_id', $landlordId)
                    ->orWhereHas('property', fn ($property) => $property->where('landlord_id', $landlordId));
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
            ->orderBy('start_date')
            ->get();
    }

    public function exportToCsv()
    {
        $this->isExporting = true;
        $invoices = $this->yearInvoices();
        $fileName = 'vat_report_'.$this->year.'_'.now()->format('Y-m-d').'.csv';

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['VAT REPORT '.$this->year]);
            fputcsv($file, ['Reporting Period: Jan 1 - Dec 31, '.$this->year]);
            fputcsv($file, []);
            fputcsv($file, ['Invoice No', 'Client', 'Property', 'House', 'Start', 'End', 'Rental Amount', 'VAT', 'Total Billed']);

            foreach ($invoices as $invoice) {
                $amount = (float) $invoice->amount;
                $vat = (float) $invoice->vat;
                fputcsv($file, [
                    $invoice->invoice_no,
                    $invoice->tenant->tenant_name ?? $invoice->tenant->company_name ?? '—',
                    $invoice->property->name ?? '—',
                    optional(optional($invoice->unit)->house)->name ?? '—',
                    optional($invoice->start_date)?->format('Y-m-d'),
                    optional($invoice->end_date)?->format('Y-m-d'),
                    number_format($amount, 2),
                    number_format($vat, 2),
                    number_format($amount + $vat, 2),
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, [
                'TOTALS',
                '',
                '',
                '',
                '',
                '',
                number_format($invoices->sum('amount'), 2),
                number_format($invoices->sum('vat'), 2),
                number_format($invoices->sum(fn ($invoice) => (float) $invoice->amount + (float) $invoice->vat), 2),
            ]);
            fclose($file);
        };

        $this->isExporting = false;

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ]);
    }

    public function render()
    {
        $invoices = $this->yearInvoices();
        $totalAmount = (float) $invoices->sum('amount');
        $totalVat = (float) $invoices->sum('vat');
        $totalBilled = $totalAmount + $totalVat;

        $rows = $invoices->groupBy(function ($invoice) {
            $house = optional($invoice->unit)->house;
            return ($invoice->property_id ?? 'none').'-'.($house->id ?? 'none');
        })->map(function ($group) {
            $first = $group->first();
            $house = optional($first->unit)->house;
            $property = $first->property;
            $amount = (float) $group->sum('amount');
            $vat = (float) $group->sum('vat');

            return [
                'district' => optional($property?->districtRelation)->name ?? 'Unknown',
                'property_name' => $property->name ?? '—',
                'property_upi' => $property->upi ?? '—',
                'house_name' => $house->name ?? '—',
                'invoices_count' => $group->count(),
                'amount' => $amount,
                'vat' => $vat,
                'total' => $amount + $vat,
            ];
        })->values();

        $byDistrict = $rows->groupBy('district');

        return view('livewire.user.land-lord.vat.vat-livewire', [
            'invoices' => $invoices,
            'rows' => $rows,
            'byDistrict' => $byDistrict,
            'totalAmount' => $totalAmount,
            'totalVat' => $totalVat,
            'totalBilled' => $totalBilled,
            'availableYears' => $this->availableYears(),
        ]);
    }
}
