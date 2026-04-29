<?php

namespace App\Livewire\User\LandLord\House;

use App\Models\House;
use App\Models\HouseAdjacement;
use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class HouseCrudLivewire extends Component
{
    use WithPagination;

    public $house_id;
    public $name;
    public $floor;
    public $description;
    public $showModal = false;
    public $properties = [];
    public $selected_property_id;
    public $confirmingDelete = false;
    public $houseToDelete;

    protected $rules = [
        'selected_property_id' => 'required|exists:properties,id',
        'name' => 'required|string|max:255',
        'floor' => 'required|integer|min:0',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $landlordId = Auth::user()->landlord_id;
        $this->properties  = Property::where('landlord_id', $landlordId)->get();
    }

    public function confirmDelete($houseId)
    {
        $this->confirmingDelete = true;
        $this->houseToDelete = $houseId;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->houseToDelete = null;
    }

    public function deleteHouseConfirmed()
    {
        House::findOrFail($this->houseToDelete)->delete();
        $this->dispatch('show-success-message', message: 'House deleted successfully.');
        $this->confirmingDelete = false;
        $this->houseToDelete = null;
        $this->resetPage();
    }

    public function createHouse()
    {
        $this->resetHouseFields();
        $this->showModal = true;
    }

    public function editHouse($houseId)
    {
        $house = House::findOrFail($houseId);
        $this->house_id = $house->id;
        $this->selected_property_id = $house->property_id;
        $this->name = $house->name;
        $this->floor = $house->floor;
        $this->description = $house->description;
        $this->showModal = true;
    }

    public function storeHouse()
    {
        $this->validate();

        $house = House::updateOrCreate(
            ['id' => $this->house_id],
            [
                'property_id' => $this->selected_property_id,
                'name' => $this->name,
                'floor' => $this->floor,
                'description' => $this->description,
            ],
        );

        HouseAdjacement::firstOrCreate(
            [
                'landlord_id' => Auth::user()->landlord_id,
                'house_id' => $house->id,
                'year' => now()->year,
            ],
            [
                'house_value' => 0,
                'value_per_m' => 0,
                'tax_rate' => 0,
            ],
        );
        $this->dispatch('show-success-message', message: $this->house_id ? 'House updated successfully.' : 'House created successfully.');

        $this->closeModal();
        $this->resetPage();
    }

    public function deleteHouse($houseId)
    {
        House::findOrFail($houseId)->delete();
        $this->dispatch('show-success-message', message: 'House deleted successfully.');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetHouseFields();
        $this->resetErrorBag();
    }

    private function resetHouseFields()
    {
        $this->reset(['house_id', 'selected_property_id', 'name', 'floor', 'description']);
    }

    public function render()
    {
        $houses = House::whereHas('property', function ($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->paginate(10);

        return view('livewire.user.land-lord.house.house-crud-livewire', [
            'houses' => $houses,
            'properties' => $this->properties,
        ]);
    }
}
