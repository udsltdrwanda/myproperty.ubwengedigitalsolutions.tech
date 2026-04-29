<?php

namespace App\Http\Controllers\user\landlord\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\House;
use App\Models\PropertyUnit;

class ImportUnitController extends Controller
{
    public function index()
    {
        return view('user.landlord.import.unit-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());

        $sheetNames = $spreadsheet->getSheetNames();
        $targetSheetName = 'Room_Records';
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
            return redirect()->back()->with('error', 'Room_Records sheet not found, using active sheet instead.');
        }
        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(3, $row_limit);

        $imported_count = 0;

        foreach ($row_range as $rowNumber) {
            $upi = trim($sheet->getCell('A' . $rowNumber)->getValue());
            if (empty($upi)) {
                continue;
            }

            $house_name = trim($sheet->getCell('B' . $rowNumber)->getValue());
            $name = trim($sheet->getCell('C' . $rowNumber)->getValue());
            $monthly_rent = (float) str_replace(',', '', trim($sheet->getCell('D' . $rowNumber)->getValue()));
            $room_use = trim($sheet->getCell('E' . $rowNumber)->getValue());

            // Get the property ID based on UPI
            $property_id = Property::where('upi', $upi)->value('id');

            if (!$property_id) {
                Log::info("Property with UPI {$upi} not found, skipping row {$rowNumber}");
                continue;
            }

            // Get the house ID based on property ID and house name
            $house_id = House::where('property_id', $property_id)->where('name', $house_name)->value('id');

            if (!$house_id) {
                Log::info("House named {$house_name} not found for property {$upi}, skipping row {$rowNumber}");
                continue;
            }
            // Create or update the property unit
            $property_unit = PropertyUnit::updateOrCreate(
                [
                    'house_id' => $house_id,
                    'name' => $name,
                ],
                [
                    'roomNumber'=> $name,
                    'floor' => null,
                    'rent' => $monthly_rent,
                    'type' => $room_use,
                    'rentTypes'=>'Monthly',
                    'unit_status' => 'vacant',
                ],
            );

            $imported_count++;
        }

        return redirect()
            ->back()
            ->with('success', "Unit data imported successfully! {$imported_count} records processed.");
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
            \Log::warning("Manual date parsing failed for: {$dateCell}");
        }

        return null;
    }
}
