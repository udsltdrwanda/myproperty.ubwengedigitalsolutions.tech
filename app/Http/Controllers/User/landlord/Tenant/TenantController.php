<?php

namespace App\Http\Controllers\User\landlord\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return view('user.landlord.tenant.index');
    }
}
