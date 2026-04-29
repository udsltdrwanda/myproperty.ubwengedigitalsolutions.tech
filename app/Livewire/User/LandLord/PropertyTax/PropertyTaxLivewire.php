<?php

namespace App\Livewire\User\LandLord\PropertyTax;

use App\Models\Property;
use App\Models\DistrictAdjacement;
use App\Models\LandAdjacement;
use App\Models\HouseAdjacement;
use App\Models\House;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PropertyTaxLivewire extends Component
{
    public function render()
    {
        $userId = Auth::user()->landlord_id;
        $currentYear = Carbon::now()->year;

        $propertiesByDistrict = Property::with('districtRelation')
            ->where('landlord_id', $userId)
            ->select('district', DB::raw('count(*) as property_count'))
            ->groupBy('district')
            ->get()
            ->map(function ($item) use ($userId, $currentYear) {
                $properties = Property::where('district', $item->district)
                    ->where('landlord_id', $userId)
                    ->with(['sectorRelation', 'cellRelation', 'villageRelation'])
                    ->get();

                    $properties = $properties->map(function ($property) use ($currentYear) {
                        $land = LandAdjacement::where('property_id', $property->id)
                            ->where('landlord_id', $property->landlord_id)
                            ->where('year', $currentYear)
                            ->first();

                        $houseIds = House::where('property_id', $property->id)->pluck('id');

                        $houseAdjacements = HouseAdjacement::whereIn('house_id', $houseIds)
                            ->where('landlord_id', $property->landlord_id)
                            ->where('year', $currentYear)
                            ->get();

                        $buildingValue = $houseAdjacements->sum('house_value');

                        $buildingTax = $houseAdjacements->sum(function ($house) {
                            return ($house->house_value ?? 0) * ($house->tax_rate ?? 0);
                        });
                        $property->land_value = $land->land_value ?? 0;
                        $property->value_per_m = $land->value_per_m ?? 0;
                        $property->land_tax_rate = $land->tax_rate ?? 0;
                        $property->building_value = $buildingValue;
                        $property->building_tax = $buildingTax;

                        return $property;
                    });


                return [
                    'district' => $item->district,
                    'district_name' => optional($item->districtRelation)->name ?? 'Unknown',
                    'property_count' => $item->property_count,
                    'properties' => $properties
                ];
            });

        return view('livewire.user.land-lord.property-tax.property-tax-livewire', [
            'propertiesByDistrict' => $propertiesByDistrict
        ]);
    }
}
