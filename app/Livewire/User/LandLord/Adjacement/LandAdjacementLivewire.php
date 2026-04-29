<?php

namespace App\Livewire\User\LandLord\Adjacement;

use App\Models\LandAdjacement;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LandAdjacementLivewire extends Component
{
    public $records = [];
    public $properties = [];
    public $property = [];
    public $newRecord = [
        'property_id' => '',
        'year' => '',
        'value_per_m' => '',
        'tax_rate' => '',
        'land_value' => '',
    ];

    public $confirmingDelete = false;
    public $recordIdToDelete = null;

    public $showEditModal = false;
    public $isNewRecord = true;

    public function mount()
    {
        $this->loadLand();
        $this->properties = Property::where('landlord_id', Auth::user()->landlord_id)
            ->select('id', 'upi', 'name')
            ->get()
            ->toArray();
    }

    public function loadLand()
    {
        $this->records = LandAdjacement::with([
                'property.provinceRelation',
                'property.districtRelation',
                'property.sectorRelation',
                'property.cellRelation',
                'property.villageRelation'
            ])
            ->where('landlord_id', Auth::user()->landlord_id)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'property_id' => $record->property_id,
                    'landlord_id' => $record->landlord_id,
                    'value_per_m' => $record->value_per_m,
                    'tax_rate' => $record->tax_rate,
                    'land_value' => $record->land_value,
                    'year' => $record->year,
                    'upi' => $record->property->upi ?? '',
                    'name' => $record->property->name ?? '',
                    'district' => $record->property->districtRelation->name ?? '',
                    'sector' => $record->property->sectorRelation->name ?? '',
                    'cell' => $record->property->cellRelation->name ?? '',
                    'village' => $record->property->villageRelation->name ?? '',
                ];
            })
            ->toArray();
    }

    public function confirmDelete($id)
    {
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
            $record = LandAdjacement::find($this->recordIdToDelete);

            if ($record && $record->landlord_id == Auth::user()->landlord_id) {
                $record->delete();
                $this->dispatch('show-success-message', message: 'Land record deleted successfully');
            } else {
                $this->dispatch('show-error-message', message: 'Unable to delete the record');
            }

            $this->confirmingDelete = false;
            $this->recordIdToDelete = null;
            $this->loadLand();
        }
    }

    public function openEditModal($id = null)
    {
        if ($id) {
            $record = LandAdjacement::findOrFail($id);
            $this->newRecord = $record->toArray();
            $this->property = $record->property;
            $this->isNewRecord = false;
        } else {
            $this->reset('newRecord', 'property');
            $this->newRecord['year'] = now()->year;
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
            'newRecord.property_id' => 'required|exists:properties,id',
            'newRecord.year' => 'required|integer',
            'newRecord.value_per_m' => 'required|numeric',
            'newRecord.tax_rate' => 'required|numeric',
            'newRecord.land_value' => 'required|numeric',
        ]);

        LandAdjacement::updateOrCreate(
            [
                'property_id' => $this->newRecord['property_id'],
                'landlord_id' => Auth::user()->landlord_id,
                'year' => $this->newRecord['year'],
            ],
            [
                'value_per_m' => $this->newRecord['value_per_m'],
                'tax_rate' => $this->newRecord['tax_rate'],
                'land_value' => $this->newRecord['land_value'],
            ]
        );

        $this->dispatch('show-success-message', message: $this->isNewRecord ? 'Record created.' : 'Record updated.');
        $this->showEditModal = false;
        $this->loadLand();
    }

    public function render()
    {
        return view('livewire.user.land-lord.adjacement.land-adjacement-livewire', [
            'records' => $this->records,
            'properties' => $this->properties,
            'property' => $this->property,
        ])->layout('layouts.app');
    }
}
