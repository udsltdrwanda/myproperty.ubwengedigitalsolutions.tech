<?php

namespace App\Livewire\User\LandLord\Adjacement;

use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\DistrictAdjacement;

class AdjacementLivewire extends Component
{
    public $districts = [];
    public $confirmingDelete = false;
    public $deleteIndex = null;

    public function mount()
    {
        $this->loadDistricts();
    }
    public function confirmDelete($index)
    {
        $this->deleteIndex = $index;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        $district = $this->districts[$this->deleteIndex];

        DistrictAdjacement::where('district_id', $district['district_id'])
            ->where('landlord_id', Auth::user()->landlord_id)
            ->where('year', $district['year'])
            ->delete();

        $this->dispatch('show-success-message', message: 'District tax deleted successfully for ' . ($district['district_name'] ?? 'Unknown District'));

        $this->confirmingDelete = false;
        $this->deleteIndex = null;
        $this->loadDistricts();
    }

    public function loadDistricts()
    {
        $this->districts = DistrictAdjacement::with('districtRelation')
            ->where('landlord_id', Auth::user()->landlord_id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'district_id' => $item->district_id,
                    'district_name' => $item->districtRelation->name ?? 'Unknown',
                    'year' => $item->year,
                    'value_per_m' => $item->value_per_m,
                    'tax_rate' => $item->tax_rate,
                ];
            })
            ->toArray();
    }

    public function updateDistrict($index)
    {
        $district = $this->districts[$index];
        DistrictAdjacement::updateOrCreate(
            [
                'district_id' => $district['district_id'],
                'landlord_id' => Auth::user()->landlord_id,
                'year' => $district['year'] ?? now()->year,
            ],
            [
                'value_per_m' => $district['value_per_m'] ?? 0,
                'tax_rate' => $district['tax_rate'] ?? 0,
            ],
        );
        $this->dispatch('show-success-message', message: 'District tax updated successfully for ' . ($district['district_name'] ?? 'Unknown District'));
        $this->loadDistricts();
    }

    public function render()
    {
        return view('livewire.user.land-lord.adjacement.adjacement-livewire', [])->layout('layouts.app');
    }
}
