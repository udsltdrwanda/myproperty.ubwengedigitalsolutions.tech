<?php

namespace App\Http\Controllers\User\landlord\PropertyTax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyTaxController extends Controller
{
    public function index()
    {
        return view('user.landlord.property-tax.index');
    }
}
