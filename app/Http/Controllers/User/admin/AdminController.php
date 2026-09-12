<?php

namespace App\Http\Controllers\User\admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserRequest;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $landlords = User::where('user_role', 'LANDLORD')->count();
        $pendingRequests = UserRequest::where('status', 'pending')->count();
        $approvedRequests = UserRequest::where('status', 'approved')->count();
        $totalProperties = Property::count();
        $totalTenants = Tenant::count();
        $totalInvoices = Invoice::count();
        $recentRequests = UserRequest::latest()->limit(6)->get();

        return view('user.admin.dashboard', compact(
            'totalUsers',
            'landlords',
            'pendingRequests',
            'approvedRequests',
            'totalProperties',
            'totalTenants',
            'totalInvoices',
            'recentRequests'
        ));
    }
}
