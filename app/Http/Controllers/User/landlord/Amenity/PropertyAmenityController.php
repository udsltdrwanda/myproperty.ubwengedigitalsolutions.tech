<?php

namespace App\Http\Controllers\User\landlord\Amenity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyAmenityController extends Controller
{
    public function index()
    {
        return view('user.landlord.amenity.index');
    }
}
