<?php

namespace App\Http\Controllers\User\tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantDashboardController extends Controller
{
    public function index()
    {
        return view('user.tenant.dashboard');
    }
}
