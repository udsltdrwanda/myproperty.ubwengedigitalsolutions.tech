<?php

namespace App\Http\Controllers\user\landlord\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\District;
use App\Models\Sector;
use App\Models\Cell;
use App\Models\Province;
use App\Models\Village;
use App\Models\LandAdjacement;

class ImportLandController extends Controller
{
    public function index()
    {
        return view('user.landlord.import.land-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(3, $row_limit);

        // Arrays to track successful and skipped rows
        $successRows = [];
        $skippedRows = [];
        $skipReasons = [];

        foreach ($row_range as $rowNumber) {
            $upi = trim($sheet->getCell('A' . $rowNumber)->getValue());
            if (empty($upi)) {
                $skippedRows[] = $rowNumber;
                $skipReasons[$rowNumber] = 'Empty UPI';
                continue;
            }

            $name = trim($sheet->getCell('B' . $rowNumber)->getValue());
            $provinceName = trim($sheet->getCell('C' . $rowNumber)->getValue());
            $districtName = trim($sheet->getCell('D' . $rowNumber)->getValue());
            $sectorName = trim($sheet->getCell('E' . $rowNumber)->getValue());
            $cellName = trim($sheet->getCell('F' . $rowNumber)->getValue());
            $villageName = trim($sheet->getCell('G' . $rowNumber)->getValue());
            $property_use = trim($sheet->getCell('H' . $rowNumber)->getValue());
            $area = str_replace(',', '', trim($sheet->getCell('I' . $rowNumber)->getValue()));
            $property_value = (float) str_replace(',', '', trim($sheet->getCell('J' . $rowNumber)->getValue()));
            $tax_rate = trim($sheet->getCell('K' . $rowNumber)->getValue());
            $registered_date = trim($sheet->getCell('L' . $rowNumber)->getValue());
            $registered_at = $this->parseDate($registered_date);

            // Validate required fields
            if (empty($name) || empty($provinceName) || empty($districtName) || empty($sectorName) || empty($cellName) || empty($villageName)) {
                $skippedRows[] = $rowNumber;
                $skipReasons[$rowNumber] = 'Missing required location data';
                continue;
            }

            // Lookup location IDs
            $provinceId = Province::where('name', $provinceName)->value('id');
            $districtId = District::where('name', $districtName)->value('id');
            $sectorId = Sector::where('name', $sectorName)->value('id');
            $cellId = Cell::where('name', $cellName)->value('id');
            $villageId = Village::where('name', $villageName)->value('id');

            if (!$provinceId || !$districtId || !$sectorId || !$cellId || !$villageId) {
                $skippedRows[] = $rowNumber;
                $missing = [];
                if (!$provinceId) {
                    $missing[] = "Province: $provinceName";
                }
                if (!$districtId) {
                    $missing[] = "District: $districtName";
                }
                if (!$sectorId) {
                    $missing[] = "Sector: $sectorName";
                }
                if (!$cellId) {
                    $missing[] = "Cell: $cellName";
                }
                if (!$villageId) {
                    $missing[] = "Village: $villageName";
                }

                $skipReasons[$rowNumber] = 'Location not found: ' . implode(', ', $missing);
                Log::warning("Location not found at row $rowNumber: " . implode(', ', $missing));
                continue;
            }

            try {
                $property = Property::updateOrCreate(
                    ['upi' => $upi],
                    [
                        'country' => 'Rwanda',
                        'name' => $name,
                        'province' => $provinceId,
                        'district' => $districtId,
                        'sector' => $sectorId,
                        'cell' => $cellId,
                        'village' => $villageId,
                        'property_use' => $property_use,
                        'area' => $area,
                        'property_value' => $property_value,
                        'tax_rate' => $tax_rate,
                        'owned_year' => $registered_at,
                        'description' => 'Imported',
                        'user_id' => Auth::id(),
                        'landlord_id' => Auth::user()->landlord_id,
                        'status' => true,
                    ],
                );

                LandAdjacement::updateOrCreate(
                    [
                        'landlord_id' => Auth::user()->landlord_id,
                        'property_id' => $property->id,
                        'year' => now()->year,
                    ],
                    [
                        'land_value' => $property_value,
                        'value_per_m' => $tax_rate,
                        'tax_rate' => $tax_rate,
                    ],
                );

                // Record successful import
                $successRows[] = $rowNumber;
            } catch (\Exception $e) {
                // Record failed import with reason
                $skippedRows[] = $rowNumber;
                $skipReasons[$rowNumber] = 'Database error: ' . $e->getMessage();
                Log::error("Error importing row $rowNumber: " . $e->getMessage());
            }
        }

        // Store import results in session
        session()->flash('import_results', [
            'success_count' => count($successRows),
            'success_rows' => $successRows,
            'skipped_count' => count($skippedRows),
            'skipped_rows' => $skippedRows,
            'skip_reasons' => $skipReasons,
        ]);

        // Prepare skip reasons summary for display
        $skipReasonsSummary = [];
        if (!empty($skippedRows)) {
            foreach ($skipReasons as $rowNumber => $reason) {
                $skipReasonsSummary[] = "Row $rowNumber: $reason";
            }
        }

        // Build the basic success message
        $message = 'Land data import completed. ' . count($successRows) . ' rows imported successfully, ' . count($skippedRows) . ' rows skipped.';
        if (!empty($skipReasonsSummary)) {
            $message .= ' Skip reasons: ' . implode('; ', $skipReasonsSummary);
        }

        return redirect()
            ->back()
            ->with([
                'success' => $message,
                'skip_reasons' => $skipReasons,
            ]);
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
}
