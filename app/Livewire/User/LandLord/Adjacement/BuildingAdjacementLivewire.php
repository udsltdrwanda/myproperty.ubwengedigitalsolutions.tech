<?php

namespace App\Livewire\User\LandLord\Adjacement;

use App\Models\HouseAdjacement;
use App\Models\House;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BuildingAdjacementLivewire extends Component
{
    public $records = [];
    public $confirmingDelete = false;
    public $recordIdToDelete = null;

    public $showEditModal = false;
    public $isNewRecord = true;

    public $newRecord = [
        'year' => null,
        'house_value' => null,
        'value_per_m' => null,
        'tax_rate' => null,
        'house_id' => null,
    ];

    public $houses = [];
    public $house = [];
    public $selectedHouseId = null;

    public function mount()
    {
        $this->loadHouses();

        $this->houses = House::with('property')
            ->whereHas('property', function ($query) {
                $query->where('landlord_id', Auth::user()->landlord_id);
            })
            ->get()
            ->map(function ($house) {
                return [
                    'id' => $house->id,
                    'name' => $house->name,
                    'upi' => $house->property->upi ?? '',
                ];
            })
            ->toArray();
    }

    public function loadHouses()
    {
        $this->records = HouseAdjacement::with(['house.property'])
            ->whereHas('house.property', function ($query) {
                $query->where('landlord_id', Auth::user()->landlord_id);
            })
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'year' => $record->year,
                    'house_value' => $record->house_value,
                    'value_per_m' => $record->value_per_m,
                    'tax_rate' => $record->tax_rate,
                    'house_id' => $record->house->id,
                    'house_name' => $record->house->name ?? '',
                    'floor' => $record->house->floor ?? '',
                    'description' => $record->house->description ?? '',
                    'upi' => $record->house->property->upi ?? '',
                ];
            })
            ->toArray();
    }

    public function showCreateModal()
    {
        $this->reset('newRecord');
        $this->house = [];
        $this->isNewRecord = true;
        $this->showEditModal = true;

        // If you need to select a house for creation, initialize selectedHouseId
        $this->selectedHouseId = null;
    }

    public function selectHouse($houseId)
    {
        $selectedHouse = collect($this->houses)->firstWhere('id', $houseId);
        if ($selectedHouse) {
            $this->selectedHouseId = $houseId;
            $this->newRecord['house_id'] = $houseId;
            $this->house = [
                'name' => $selectedHouse['name'],
                'upi' => $selectedHouse['upi'],
            ];
        }
    }

    public function openEditModal($id = null)
    {
        if ($id) {
            $record = HouseAdjacement::with('house.property')->findOrFail($id);

            $this->newRecord = [
                'year' => $record->year,
                'house_value' => $record->house_value,
                'value_per_m' => $record->value_per_m,
                'tax_rate' => $record->tax_rate,
                'house_id' => $record->house_id,
            ];

            $this->house = [
                'name' => $record->house->name ?? '',
                'upi' => $record->house->property->upi ?? '',
            ];

            $this->selectedHouseId = $record->house_id;
            $this->isNewRecord = false;
        } else {
            $this->newRecord = [
                'year' => now()->year,
                'house_value' => null,
                'value_per_m' => null,
                'tax_rate' => null,
                'house_id' => null,
            ];

            $this->house = [];
            $this->selectedHouseId = null;
            $this->isNewRecord = true;
        }

        $this->showEditModal = true;
    }
    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function saveRecord()
    {
        $this->validate([
            'newRecord.house_id' => 'required|exists:houses,id',
            'newRecord.year' => 'required|numeric|min:2000|max:' . now()->year,
            'newRecord.house_value' => 'required|numeric|min:0',
            'newRecord.value_per_m' => 'nullable|numeric|min:0',
            'newRecord.tax_rate' => 'nullable|numeric|min:0',
        ]);

        // For updates, verify the house belongs to this landlord
        if (!$this->isNewRecord) {
            $house = House::with('property')->find($this->newRecord['house_id']);
            if (!$house || $house->property->landlord_id != Auth::user()->landlord_id) {
                $this->dispatch('show-error-message', message: 'You do not have permission to edit this record');
                return;
            }
        }

        HouseAdjacement::updateOrCreate(
            [
                'house_id' => $this->newRecord['house_id'],
                'landlord_id' => Auth::user()->landlord_id,
                'year' => $this->newRecord['year'],
            ],
            [
                'house_value' => $this->newRecord['house_value'],
                'value_per_m' => $this->newRecord['value_per_m'],
                'tax_rate' => $this->newRecord['tax_rate'],
            ],
        );

        $this->dispatch('show-success-message', message: 'House record saved successfully.');
        $this->closeEditModal();
        $this->loadHouses();
    }

    public function confirmDelete($id)
    {
        // Verify record exists and belongs to this landlord before showing delete modal
        $record = HouseAdjacement::with('house.property')->find($id);

        if (!$record || $record->house->property->landlord_id != Auth::user()->landlord_id) {
            $this->dispatch('show-error-message', message: 'You do not have permission to delete this record');
            return;
        }

        $this->confirmingDelete = true;
        $this->recordIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->recordIdToDelete = null;
    }

    public function deleteRecord()
    {
        if ($this->recordIdToDelete) {
            $record = HouseAdjacement::with('house.property')->find($this->recordIdToDelete);

            if (!$record) {
                $this->dispatch('show-error-message', message: 'Record not found');
                $this->cancelDelete();
                return;
            }

            // Double-check ownership
            if ($record->house->property->landlord_id != Auth::user()->landlord_id) {
                $this->dispatch('show-error-message', message: 'You do not have permission to delete this record');
                $this->cancelDelete();
                return;
            }

            $record->delete();
            $this->dispatch('show-success-message', message: 'House record deleted successfully');
            $this->confirmingDelete = false;
            $this->recordIdToDelete = null;
            $this->loadHouses();
        }
    }

    public function render()
    {
        return view('livewire.user.land-lord.adjacement.building-adjacement-livewire', [
            'records' => $this->records,
            'houses' => $this->houses,
            'house' => $this->house,
            'isNewRecord' => $this->isNewRecord,
            'newRecord' => $this->newRecord,
            'showEditModal' => $this->showEditModal,
            'selectedHouseId' => $this->selectedHouseId,
        ])->layout('layouts.app');
    }
}
