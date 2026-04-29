<?php

namespace App\Livewire\User\LandLord\UnitAmenities;

use App\Models\Property;
use App\Models\UnitAmenities;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UnitAmenitiesLivewire extends Component
{
    use WithPagination;

    public $properties = [];
    public $amenity_id;
    public $name;
    public $property_id;
    public $showModal = false;
    public $confirmingDeletion = false;
    public $amenityIdBeingDeleted = null;
    public $selected_property_id = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'property_id' => 'required|exists:properties,id',
    ];

    public function mount()
    {
        $landlordId = Auth::user()->landlord_id;
        $this->properties  = Property::where('landlord_id', $landlordId)->get();

    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectedPropertyId()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createAmenity()
    {
        $this->resetInputFields();
        $this->showModal = true;

        if ($this->selected_property_id) {
            $this->property_id = $this->selected_property_id;
        }
    }

    public function editAmenity($amenityId)
    {
        $amenity = UnitAmenities::findOrFail($amenityId);

        $this->amenity_id = $amenityId;
        $this->name = $amenity->name;
        $this->property_id = $amenity->property_id;

        $this->showModal = true;
    }

    public function confirmDeleteAmenity($amenityId)
    {
        $this->amenityIdBeingDeleted = $amenityId;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->amenityIdBeingDeleted = null;
    }

    public function deleteAmenityConfirmed()
    {
        UnitAmenities::find($this->amenityIdBeingDeleted)->delete();
        $this->dispatch('show-success-message', message: 'Amenity Deleted Successfully.');

        $this->confirmingDeletion = false;
        $this->amenityIdBeingDeleted = null;
    }

    public function storeAmenity()
    {
        $this->validate();

        UnitAmenities::updateOrCreate(
            ['id' => $this->amenity_id,
                        'property_id' => $this->property_id],
            [
                'name' => $this->name,
                'landlord_id' => Auth::user()->landlord_id,
            ],
        );

        $this->dispatch('show-success-message', message: $this->amenity_id ? 'Amenity Updated Successfully.' : 'Amenity Created Successfully.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->amenity_id = null;
        $this->name = '';
        $this->property_id = $this->selected_property_id ?: '';
    }

    public function render()
    {
        $landlordId = Auth::user()->landlord_id;
        $propertyIds = Property::where('landlord_id', $landlordId)->get()->pluck('id')->toArray();

        $query = UnitAmenities::where('landlord_id', Auth::user()->landlord_id);

        if ($this->selected_property_id) {
            $query->where('property_id', $this->selected_property_id);
        } else {
            $query->whereIn('property_id', $propertyIds);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $amenities = $query->with('property')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.user.land-lord.unit-amenities.unit-amenities-livewire', [
            'amenities' => $amenities,
        ])->layout('layouts.app');
    }
}
