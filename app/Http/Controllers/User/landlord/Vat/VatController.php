<?php

namespace App\Http\Controllers\User\landlord\Vat;

use App\Http\Controllers\Controller;

class VatController extends Controller
{
    public function index()
    {
        return view('user.landlord.vat.index');
    }
}
