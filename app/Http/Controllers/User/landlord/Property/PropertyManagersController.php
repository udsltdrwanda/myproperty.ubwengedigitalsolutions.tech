<?php

namespace App\Http\Controllers\User\landlord\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyManagersController extends Controller
{
    public function index()
    {
        return view('user.landlord.property.manager');
    }

}
