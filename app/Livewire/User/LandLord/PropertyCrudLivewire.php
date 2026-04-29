<?php

namespace App\Livewire\User\LandLord;

use Livewire\Component;
use App\Models\Property;
use App\Models\Province;
use App\Models\District;
use App\Models\Sector;
use App\Models\Cell;
use App\Models\DistrictAdjacement;
use App\Models\Village;
use App\Models\House;
use App\Models\LandAdjacement;
use App\Models\UnitAmenities;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class PropertyCrudLivewire extends Component
{
    use WithPagination;

    // Property CRUD fields
    public $provinces;
    public $districts = [];
    public $sectors = [];
    public $cells = [];
    public $villages = [];
    public $property_id;
    public $name;
    public $upi;
    public $description;
    public $property_use;
    public $country;
    public $province_id;
    public $district_id;
    public $sector_id;
    public $cell_id;
    public $village_id;
    public $area;
    public $owned_year;
    public $propertyToDelete = null;
    public $showDeleteModal = false;
    public $isOpen = false;
    public $showUnitModal = false;
    public $allAmenities;
    public $property_value;

    public function mount()
    {
        $this->provinces = Province::all();
        $this->allAmenities = UnitAmenities::all();
    }

    public function getPropertiesQuery()
    {
        return Property::with(['houses', 'user', 'provinceRelation', 'districtRelation', 'sectorRelation', 'cellRelation', 'villageRelation'])->where( 'landlord_id',Auth::user()->landlord_id,);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    private function resetInputFields()
    {
        $this->property_id = null;
        $this->name = '';
        $this->upi = '';
        $this->description = '';
        $this->country = '';
        $this->province_id = null;
        $this->district_id = null;
        $this->sector_id = null;
        $this->cell_id = null;
        $this->village_id = null;
    }

    public function exportToExcel()
    {
        $properties = $this->getPropertiesQuery()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['UPI', 'District', 'Sector', 'Cell', 'Village', 'Property Use', 'Area (m²)', 'Owned Year'];

        $column = 1;
        foreach ($headers as $header) {
            $cellReference = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($column) . '1';
            $sheet->setCellValue($cellReference, $header);
            $column++;
        }

        // Set data rows
        $row = 2;
        foreach ($properties as $property) {
            $sheet->setCellValue('A' . $row, $property->upi);
            $sheet->setCellValue('B' . $row, optional($property->districtRelation)->name ?? '');
            $sheet->setCellValue('C' . $row, optional($property->sectorRelation)->name ?? '');
            $sheet->setCellValue('D' . $row, optional($property->cellRelation)->name ?? '');
            $sheet->setCellValue('E' . $row, optional($property->villageRelation)->name ?? '');
            $sheet->setCellValue('F' . $row, $property->property_use);
            $sheet->setCellValue('G' . $row, $property->area);
            $sheet->setCellValue('H' . $row, $property->owned_year ? date('d-m-Y', strtotime($property->owned_year)) : '');
            $row++;
        }

        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D8D8D8',
                ],
            ],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'properties_' . date('D-M-Y') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $fileContent = ob_get_clean();

        return response()->streamDownload(
            function () use ($fileContent) {
                echo $fileContent;
            },
            $fileName,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        );
    }

    public function exportToPdf()
    {
        $properties = $this->getPropertiesQuery()->get();
        $pdf = PDF::loadView('exports.properties-pdf', ['properties' => $properties]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'properties.pdf');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'upi' => 'required|unique:properties,upi,' . $this->property_id,
            'description' => 'nullable',
            'province_id' => 'required',
            'district_id' => 'required',
            'sector_id' => 'required',
            'cell_id' => 'required',
            'village_id' => 'required',
            'property_use' => 'required',
            'area' => 'required|numeric',
            'owned_year' => 'nullable|date|before_or_equal:today',
        ]);
        $propery = Property::updateOrCreate(
            ['id' => $this->property_id],
            [
                'user_id' => Auth::id(),
                'landlord_id' => Auth::user()->landlord_id,
                'name' => $this->name,
                'upi' => $this->upi,
                'description' => $this->description,
                'property_use' => $this->property_use,
                'country' => $this->country,
                'province' => $this->province_id,
                'district' => $this->district_id,
                'sector' => $this->sector_id,
                'cell' => $this->cell_id,
                'village' => $this->village_id,
                'area' => $this->area,
                'owned_year' => $this->owned_year,
            ],
        );
        LandAdjacement::firstOrCreate(
            [
                'landlord_id' => Auth::user()->landlord_id,
                'property_id' => $propery->id,
                'year' => now()->year,
            ],
            [
                'land_value' => 0,
                'value_per_m' => 0,
                'tax_rate' => 0,
            ],
        );

        $this->dispatch('show-success-message', message: 'Property ' . ($this->property_id ? 'updated' : 'created') . ' successfully.');
        $this->resetPropertyFields();
        $this->closeModal();
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        $this->property_id = $id;
        $this->name = $property->name;
        $this->upi = $property->upi;
        $this->description = $property->description;
        $this->property_use = $property->property_use;
        $this->country = $property->country;
        $this->province_id = $property->province;
        $this->loadDistricts();
        $this->district_id = $property->district;
        $this->loadSectors();
        $this->sector_id = $property->sector;
        $this->loadCells();
        $this->cell_id = $property->cell;
        $this->loadVillages();
        $this->village_id = $property->village;
        $this->area = $property->area;
        $this->property_value = $property->property_value;
        if ($property->owned_year) {
            $this->owned_year = date('Y-m-d', strtotime($property->owned_year));
        } else {
            $this->owned_year = null;
        }

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->propertyToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProperty()
    {
        Property::find($this->propertyToDelete)->delete();
        $this->showDeleteModal = false;
        $this->dispatch('show-success-message', message: 'Property deleted successfully.');
    }
    public function cancelDelete()
    {
        $this->propertyToDelete = null;
        $this->showDeleteModal = false;
    }

    public function loadDistricts()
    {
        $this->districts = District::where('province_id', $this->province_id)->get();
        $this->district_id = null;
        $this->sector_id = null;
        $this->cell_id = null;
        $this->village_id = null;
        $this->sectors = [];
        $this->cells = [];
        $this->villages = [];
    }

    public function loadSectors()
    {
        $this->sectors = Sector::where('district_id', $this->district_id)->get();
        $this->sector_id = null;
        $this->cell_id = null;
        $this->village_id = null;
        $this->cells = [];
        $this->villages = [];
    }

    public function loadCells()
    {
        $this->cells = Cell::where('sector_id', $this->sector_id)->get();
        $this->cell_id = null;
        $this->village_id = null;
        $this->villages = [];
    }

    public function loadVillages()
    {
        $this->villages = Village::where('cell_id', $this->cell_id)->get();
        $this->village_id = null;
    }

    public function resetPropertyFields()
    {
        $this->reset(['property_id', 'name', 'upi', 'description', 'property_use', 'country', 'province_id', 'district_id', 'sector_id', 'cell_id', 'village_id', 'area', 'owned_year']);
        $this->reset(['districts', 'sectors', 'cells', 'villages']);
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetPropertyFields();
    }

    public function render()
    {
        return view('livewire.user.land-lord.property-crud-livewire', [
            'properties' => $this->getPropertiesQuery()->paginate(10),
        ]);
    }
}
