<?php

namespace App\Http\Controllers\User\landlord\RentalIncomeTax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentalIncomeTaxController extends Controller
{
    public function index()
    {
        return view('user.landlord.rental-income-tax.index');
    }

}
