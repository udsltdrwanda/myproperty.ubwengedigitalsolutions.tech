<?php

namespace App\Http\Controllers\User\landlord\PaymentMode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentModeController extends Controller
{
    public function index()
    {
        return view('user.landlord.payment-mode.index');
    }
}
