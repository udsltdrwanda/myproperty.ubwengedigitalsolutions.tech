<?php

namespace App\Http\Controllers\User\landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandlordDashboardController extends Controller
{
    public function index()
    {
        return view('user.landlord.dashboard');
    }
}
