<?php

namespace App\Http\Controllers\User\landlord\PaymentMode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentRecordController extends Controller
{
    public function index()
    {
        return view('user.landlord.rent-record.index');
    }
}
