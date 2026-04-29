<?php

namespace App\Http\Controllers\user\landlord\Import;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\House;
use App\Models\PropertyUnit;
use App\Models\RentRecord;
use Illuminate\Support\Carbon;

class ImportTenantContractController extends Controller
{
    public function index()
    {
        return view('user.landlord.import.import-tenant-contract');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());

        $sheetNames = $spreadsheet->getSheetNames();
        $targetSheetName = 'Rental_Contracts';
        $sheet = null;

        foreach ($sheetNames as $sheetName) {
            if (strtolower(trim($sheetName)) === strtolower(trim($targetSheetName))) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                break;
            }
        }
        // If the specified sheet doesn't exist, try the active sheet
        if (!$sheet) {
            $sheet = $spreadsheet->getActiveSheet();
            return redirect()->back()->with('error', 'Rental_Contracts sheet not found, using active sheet instead.');
        }
        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(3, $row_limit);

        $imported_count = 0;

        foreach ($row_range as $rowNumber) {
            $upi = trim($sheet->getCell('E' . $rowNumber)->getValue());
            if (empty($upi)) {
                continue;
            }
            $tin_number = trim($sheet->getCell('A' . $rowNumber)->getValue());
            $company_name = trim($sheet->getCell('B' . $rowNumber)->getValue());
            $client_name = trim($sheet->getCell('C' . $rowNumber)->getValue());
            $client_contact = trim($sheet->getCell('D' . $rowNumber)->getValue());
            $building = trim($sheet->getCell('F' . $rowNumber)->getValue());
            $room = trim($sheet->getCell('G' . $rowNumber)->getValue());

            $start = trim($sheet->getCell('I' . $rowNumber)->getValue());
            $start_date = $this->parseDate($start);

            $end = trim($sheet->getCell('J' . $rowNumber)->getValue());
            $end_date = $this->parseDate($end);
            $duration_time = $this->calculateDuration($start_date, $end_date);

            $tenant = Tenant::firstOrCreate(
                ['company_tin' => $tin_number],
                [
                    'user_id' => Auth::id(),
                    'landlord_id' => Auth::user()->landlord_id,
                    'tenant_id' => $tin_number,
                    'tenant_name' => $client_name,
                    'company_name' => $company_name,
                    'phone' => $client_contact,
                ]
            );

            $tenant_id = $tenant->id;

            $building_id = House::where('name', $building)->value('id');
            // dd($building.','.$building_id);
            // Fixed missing comma and added proper conditions
            $unit = PropertyUnit::where('house_id', $building_id)
                               ->where('roomNumber', $room)
                               ->first();

            if (!$unit) {
                Log::info("Unit with room number {$room} not found in building {$building}, skipping row {$rowNumber}");
                continue;
            }

            $unit_id = $unit->id;
            $unit_amount = $unit->rent;

            // Calculate VAT using the method
            $vat_amount = $this->calculateVat($unit_amount);


            $rent = RentRecord::updateOrCreate(
                [
                    'tenant_id' => $tenant_id,
                    'unit_id' => $unit_id,
                    'start_date' => $start_date,
                ],
                [
                    'user_id' => Auth::id(),
                    'landlord_id' => Auth::user()->landlord_id,
                    'amount' => $unit_amount,
                    'vat' => $vat_amount,
                    'start_date'=>$start_date,
                    'end_date' => $end_date,
                    'duration_time' => $duration_time,
                    'agreement_document' => null,
                ]
            );

            $imported_count++;
        }

        return redirect()
            ->back()
            ->with('success', "Contract Data data imported successfully! {$imported_count} records processed.");
    }

    private function parseDate($dateCell): ?string
    {
        if (empty($dateCell)) {
            return null;
        }

        $dateCell = trim($dateCell);
        $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'Y/m/d'];

        foreach ($formats as $format) {
            try {
                $date = \DateTime::createFromFormat($format, $dateCell);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Handle Excel numeric dates
        try {
            if (is_numeric($dateCell)) {
                return ExcelDate::excelToDateTimeObject($dateCell)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Log::warning("Could not parse Excel date: {$dateCell}");
        }

        // Fallback manual parsing
        try {
            $parts = explode('/', $dateCell);
            if (count($parts) === 3) {
                $mysqlDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
                $validDate = \DateTime::createFromFormat('Y-m-d', $mysqlDate);
                if ($validDate && $validDate->format('Y-m-d') === $mysqlDate) {
                    return $mysqlDate;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Manual date parsing failed for: {$dateCell}");
        }

        return null;
    }

    protected function calculateDuration($start_date, $end_date)
    {
        if (!$start_date || !$end_date) {
            return 0;
        }

        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $months = $start->diffInMonths($end);

        return round($months);
    }

    protected function calculateVat($amount)
    {
        return $amount > 0 ? round($amount * 0.18, 2) : 0;
    }
}
