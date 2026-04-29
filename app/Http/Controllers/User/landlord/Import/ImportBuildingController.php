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
use App\Models\HouseAdjacement;

class ImportBuildingController extends Controller
{
    public function index()
    {
        return view('user.landlord.import.building-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());

        // Use the specific sheet named "Building_Management"
        $sheet = $spreadsheet->getSheetByName('Building_Management');

        // If the specified sheet doesn't exist, try the active sheet
        if (!$sheet) {
            $sheet = $spreadsheet->getActiveSheet();
            return redirect()->back()->with('error', "Building_Management sheet not found, using active sheet instead.");
        }

        // dd($sheet);

        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(3, $row_limit);

        $imported_count = 0;

        foreach ($row_range as $rowNumber) {
            $upi = trim($sheet->getCell('A' . $rowNumber)->getValue());
            if (empty($upi)) {
                continue;
            }
            $name = trim($sheet->getCell('B' . $rowNumber)->getValue());
            $area = str_replace(',', '', trim($sheet->getCell('C' . $rowNumber)->getValue()));
            $floor_number = trim($sheet->getCell('D' . $rowNumber)->getValue());
            $building_value = (float) str_replace(',', '', trim($sheet->getCell('E' . $rowNumber)->getValue()));
            $construction_year = trim($sheet->getCell('F' . $rowNumber)->getValue());
            $tax_rate = trim($sheet->getCell('G' . $rowNumber)->getValue());

            $upi_Id = Property::where('upi', $upi)->value('id');

            if (!$upi_Id) {
                Log::info("Property with UPI {$upi} not found, skipping row {$rowNumber}");
                continue;
            }
            $house = House::updateOrCreate(
                ['name' => $name, 'property_id' => $upi_Id],
                [
                    'floor' => $floor_number,
                    'area' => $area,
                    'building_value' => $building_value,
                    'construction_year' => $construction_year,
                    'tax_rate' => $tax_rate,
                    'description' => 'Imported from Building_Management sheet',
                ],
            );
            HouseAdjacement::firstOrCreate(
                [
                    'landlord_id' => Auth::user()->landlord_id,
                    'house_id' => $house->id,
                    'year' => now()->year,
                ],
                [
                    'house_value' => $building_value,
                    'value_per_m' => 0,
                    'tax_rate' => $tax_rate,
                ],
            );

            $imported_count++;
        }

        return redirect()->back()->with('success', "Building data imported successfully! {$imported_count} records processed.");
    }
}
