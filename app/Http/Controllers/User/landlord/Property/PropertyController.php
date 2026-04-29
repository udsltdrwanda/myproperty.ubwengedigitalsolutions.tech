<?php

namespace App\Http\Controllers\User\landlord\Property;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        return view('user.landlord.property.index');
    }
    public function House()
    {
        return view('user.landlord.house.index');
    }
    public function unit()
    {
        return view('user.landlord.unit.index');
    }

    public function show($property)
    {
        $property = Property::with([
            'user',
            'provinceRelation',
            'districtRelation',
            'sectorRelation',
            'cellRelation',
            'villageRelation',
            'houses.units' // this loads units via houses
        ])->findOrFail($property);

        return view('user.landlord.property.show', compact('property'));
    }

}
