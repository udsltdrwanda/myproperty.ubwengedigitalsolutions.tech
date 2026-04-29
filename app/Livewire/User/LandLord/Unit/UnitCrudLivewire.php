<?php

namespace App\Livewire\User\LandLord\Unit;

use App\Models\House;
use App\Models\HouseAdjacement;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\UnitAmenities;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UnitCrudLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Keep all existing properties
    public $unit_id;
    public $unit_name;
    public $roomNumber;
    public $notes;
    public $rent;
    public $houseFloor;
    public $unit_status;
    public $rentTypes;
    public $type;
    public $selectedAmenities = [];
    public $currentPropertyId;
    public $currentHouseId;
    public $showUnitModal = false;
    public $properties = [];
    public $houses = [];
    public $allAmenities;
    public $isOpen = false;
    public $selected_property_id;
    public $selected_house_id;
    public $confirmingUnitDeletion = false;
    public $unitIdBeingDeleted = null;
    public $floor;
    public $newAmenityName;
    public $viewAllUnits = false;

    protected $queryString = ['page'];

    public function addAmenity()
    {
        $this->validate([
            'newAmenityName' => 'required|unique:unit_amenities,name,NULL,id,landlord_id,' . Auth::id(),
            'selected_property_id' => 'required|exists:properties,id',
        ]);

        UnitAmenities::create([
            'name' => $this->newAmenityName,
            'property_id' => $this->selected_property_id,
            'landlord_id' => Auth::id(),
        ]);

        $this->newAmenityName = '';
        $this->allAmenities = UnitAmenities::where('landlord_id', Auth::id())->get();
        $this->dispatch('show-success-message', message: 'Amenity added successfully.');
    }

    public function mount()
    {
        $this->properties = Property::where('landlord_id', Auth::user()->landlord_id)->get();
        $this->allAmenities = UnitAmenities::where('landlord_id', Auth::user()->landlord_id)->get();
        $this->loadProperties();
    }

    public function loadAllUnits()
    {
        $this->viewAllUnits = true;
        $this->selected_property_id = '';
        $this->selected_house_id = '';
        $this->resetPage();
    }

    public function confirmDeleteUnit($unitId)
    {
        $this->unitIdBeingDeleted = $unitId;
        $this->confirmingUnitDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingUnitDeletion = false;
        $this->unitIdBeingDeleted = null;
    }

    public function deleteUnitConfirmed()
    {
        PropertyUnit::find($this->unitIdBeingDeleted)->delete();
        $this->dispatch('show-success-message', message: 'Unit Deleted Successfully.');

        $this->confirmingUnitDeletion = false;
        $this->unitIdBeingDeleted = null;

        if ($this->viewAllUnits) {
            $this->loadAllUnits();
        } elseif ($this->currentHouseId) {
            $this->showUnits($this->currentHouseId);
        }
    }

    public function loadProperties()
    {
        $this->properties = Property::where('landlord_id', Auth::user()->landlord_id)->get();
    }

    public function updatedSelectedPropertyId()
    {
        if ($this->selected_property_id) {
            $this->viewAllUnits = false;
            $this->currentPropertyId = $this->selected_property_id;
            $this->loadHouses($this->selected_property_id);
            $this->selected_house_id = '';
            $this->resetPage();
        } else {
            // If no property is selected, load all units
            $this->loadAllUnits();
        }
    }

    public function updatedSelectedHouseId()
    {
        if ($this->selected_house_id) {
            $this->viewAllUnits = false;
            $this->currentHouseId = $this->selected_house_id;
            $this->showUnits($this->selected_house_id);
        } elseif ($this->selected_property_id) {
            // If house is deselected but property is selected,
            // show all units for that property
            $this->showUnitsForProperty($this->selected_property_id);
        } else {
            // If both are deselected, show all units
            $this->loadAllUnits();
        }
    }

    public function showUnitsForProperty($propertyId)
    {
        $this->viewAllUnits = false;
        $this->selected_property_id = $propertyId;
        $this->selected_house_id = '';
        $this->resetPage();
    }

    public function loadHouses($propertyId)
    {
        $this->houses = House::where('property_id', $propertyId)->get();
    }

    public function showUnits($houseId)
    {
        $this->viewAllUnits = false;
        $this->currentHouseId = $houseId;
        $house = House::findOrFail($houseId);
        $this->houseFloor = $house->floor;
        $this->resetPage();
    }

    public function createUnit($propertyId = null, $houseId = null)
    {
        $this->resetUnitInputFields();
        if ($propertyId) {
            $this->selected_property_id = $propertyId;
            $this->currentPropertyId = $propertyId;
            $this->loadHouses($propertyId);
        }

        if ($houseId) {
            $this->selected_house_id = $houseId;
            $this->currentHouseId = $houseId;
        }

        $this->showUnitModal = true;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function editUnit($unitId)
    {
        $unit = PropertyUnit::findOrFail($unitId);
        $house = House::findOrFail($unit->house_id);

        $this->unit_id = $unitId;
        $this->unit_name = $unit->name;
        $this->roomNumber = $unit->roomNumber;
        $this->notes = $unit->notes;
        $this->rent = $unit->rent;
        $this->unit_status = $unit->unit_status;
        $this->rentTypes = $unit->rentTypes;
        $this->type = $unit->type;
        $this->selectedAmenities = $unit->amenities ?? [];

        $this->selected_property_id = $house->property_id;
        $this->currentPropertyId = $house->property_id;
        $this->loadHouses($house->property_id);

        $this->selected_house_id = $unit->house_id;
        $this->currentHouseId = $unit->house_id;
        $this->floor = $unit->floor;
        $this->showUnitModal = true;
    }

    public function storeUnit()
    {
        $this->validate([
            'unit_name' => 'required|unique:property_units,name,' . $this->unit_id . ',id,house_id,' . $this->selected_house_id,
            'roomNumber' => 'required|unique:property_units,roomNumber,' . $this->unit_id . ',id,house_id,' . $this->selected_house_id,
            'rent' => 'required|numeric',
            'rentTypes' => 'required',
            'type' => 'required',
            'selected_property_id' => 'required',
            'selected_house_id' => 'required',
            'floor' => 'nullable|numeric',
        ]);

        PropertyUnit::updateOrCreate(
            ['id' => $this->unit_id],
            [
                'house_id' => $this->selected_house_id,
                'name' => $this->unit_name,
                'roomNumber' => $this->roomNumber,
                'notes' => $this->notes,
                'rent' => $this->rent,
                'rentTypes' => $this->rentTypes,
                'type' => $this->type,
                'floor' => $this->floor,
                'amenities' => $this->selectedAmenities,
            ],
        );

        $this->dispatch('show-success-message', message: $this->unit_id ? 'Unit Updated Successfully.' : 'Unit Created Successfully.');
        $this->closeUnitModal();

        // Refresh the units list based on current view mode
        if ($this->viewAllUnits) {
            $this->loadAllUnits();
        } elseif ($this->selected_house_id) {
            $this->showUnits($this->selected_house_id);
        } elseif ($this->selected_property_id) {
            $this->showUnitsForProperty($this->selected_property_id);
        }
    }

    public function deleteUnit($unitId)
    {
        PropertyUnit::find($unitId)->delete();
        $this->dispatch('show-success-message', message: 'Unit Deleted Successfully.');

        // Refresh the units list
        if ($this->viewAllUnits) {
            $this->loadAllUnits();
        } elseif ($this->currentHouseId) {
            $this->showUnits($this->currentHouseId);
        } elseif ($this->selected_property_id) {
            $this->showUnitsForProperty($this->selected_property_id);
        }
    }

    public function closeUnitModal()
    {
        $this->showUnitModal = false;
        $this->resetUnitInputFields();
    }

    private function resetUnitInputFields()
    {
        $this->unit_id = null;
        $this->unit_name = '';
        $this->roomNumber = '';
        $this->notes = '';
        $this->rent = '';
        $this->unit_status = '';
        $this->rentTypes = '';
        $this->type = '';
        $this->selectedAmenities = [];
        $this->floor = '';
    }

    public function render()
    {
        $landlordId = Auth::user()->landlord_id;

        if ($this->selected_house_id) {
            $query = PropertyUnit::with(['house.property', 'activeRentRecord'])
                ->where('house_id', $this->selected_house_id)
                ->whereHas('house.property', function ($query) use ($landlordId) {
                    $query->where('landlord_id', $landlordId);
                });
        } elseif ($this->selected_property_id) {
            $houseIds = House::where('property_id', $this->selected_property_id)
                ->whereHas('property', function ($query) use ($landlordId) {
                    $query->where('landlord_id', $landlordId);
                })
                ->pluck('id')
                ->toArray();

            $query = PropertyUnit::with(['house.property', 'activeRentRecord'])->whereIn('house_id', $houseIds);
        } else {
            $properties = Property::where('landlord_id', $landlordId)->get();
            $propertyIds = $properties->pluck('id')->toArray();
            $houseIds = House::whereIn('property_id', $propertyIds)->pluck('id')->toArray();

            $query = PropertyUnit::with(['house.property', 'activeRentRecord'])->whereIn('house_id', $houseIds);
        }

        $units = $query->paginate(10);

        return view('livewire.user.land-lord.unit.unit-crud-livewire', [
            'units' => $units,
        ]);
    }
}
