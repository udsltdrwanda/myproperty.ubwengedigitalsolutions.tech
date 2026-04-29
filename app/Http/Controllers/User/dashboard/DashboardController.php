<?php

namespace App\Http\Controllers\User\dashboard;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userRole = Auth::user()->userRole;
        if ($userRole === UserRole::ADMIN->value) {
            return redirect()->route('admin.dashboard');
        }

        if ($userRole === UserRole::LANDLORD->value) {
            return redirect()->route('landlord.dashboard');
        }
        if ($userRole === UserRole::EMPLOYEE->value) {
            return redirect()->route('employee.dashboard');
        }
        if ($userRole === UserRole::TENANT->value) {
            return redirect()->route('tenant.dashboard');
        }
        return redirect()->route('home');
    }
}
