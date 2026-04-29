<?php

namespace App\Http\Controllers\User\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class EmployeeDashboardController extends Controller
{
    public function index()
    {
        return view('user.employee.dashboard');
    }
}
