<?php

namespace App\Livewire\User\LandLord\Import;

use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Property;
use App\Models\District;
use App\Models\Sector;
use App\Models\Cell;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImportLandLivewire extends Component
{
    use WithFileUploads;
    public $file;

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($this->file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $row_limit = $sheet->getHighestDataRow();
        $row_range = range(2, $row_limit);

        foreach ($row_range as $rowNumber) {
            $upi = trim($sheet->getCell('A' . $rowNumber)->getValue());
            // Skip empty UPI
            if (empty($upi)) {
                continue;
            }

            $name = trim($sheet->getCell('B' . $rowNumber)->getValue());
            $districtName = trim($sheet->getCell('C' . $rowNumber)->getValue());
            $sectorName = trim($sheet->getCell('D' . $rowNumber)->getValue());
            $cellName = trim($sheet->getCell('E' . $rowNumber)->getValue());
            $villageName = trim($sheet->getCell('F' . $rowNumber)->getValue());
            $property_use = trim($sheet->getCell('G' . $rowNumber)->getValue());
            $area = str_replace(',', '', trim($sheet->getCell('H' . $rowNumber)->getValue()));
            $property_value = str_replace(',', '', trim($sheet->getCell('I' . $rowNumber)->getValue()));
            $property_value = (float) $property_value;
            $tax_rate = trim($sheet->getCell('J' . $rowNumber)->getValue());
            $registered_date = trim($sheet->getCell('K' . $rowNumber)->getValue());
            $registered_at = $this->parseDate($registered_date);

            // Lookup location IDs
            $districtId = District::where('name', $districtName)->value('id');
            $sectorId = Sector::where('name', $sectorName)->value('id');
            $cellId = Cell::where('name', $cellName)->value('id');
            $villageId = Village::where('name', $villageName)->value('id');

            // Skip and log if any required location is missing
            if (!$districtId || !$sectorId || !$cellId || !$villageId) {
                Log::warning("Location not found at row $rowNumber: District='$districtName', Sector='$sectorName', Cell='$cellName', Village='$villageName'");
                continue;
            }

            Property::create([
                'upi' => $upi,
                'country' => 'Rwanda',
                'name' => $name,
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
            ]);
        }
        $this->dispatch('show-success-message', message: 'Land data imported successfully!');
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

        try {
            if (is_numeric($dateCell)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateCell)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Log::warning("Could not parse Excel date: {$dateCell}");
        }

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

    public function render()
    {
        return view('livewire.user.land-lord.import.import-land-livewire')->layout('layouts.app');
    }
}
