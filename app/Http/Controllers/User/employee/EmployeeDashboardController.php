<?php

namespace App\Http\Controllers\User\employee;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\RentRecord;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $landlordId = $user->landlord_id;

        $properties = Property::where('landlord_id', $landlordId)->count();
        $units = PropertyUnit::whereHas('house.property', fn ($q) => $q->where('landlord_id', $landlordId))->count();
        $tenants = Tenant::where('landlord_id', $landlordId)->count();
        $activeContracts = RentRecord::where('landlord_id', $landlordId)->whereDate('end_date', '>=', now())->count();
        $pendingInvoices = Invoice::where('landlord_id', $landlordId)->where('invoice_status', 'Pending')->count();
        $recentInvoices = Invoice::with('tenant')->where('landlord_id', $landlordId)->latest()->limit(5)->get();

        return view('user.employee.dashboard', compact(
            'user',
            'properties',
            'units',
            'tenants',
            'activeContracts',
            'pendingInvoices',
            'recentInvoices'
        ));
    }
}
