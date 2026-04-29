<?php

namespace App\Http\Controllers\User\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('user.admin.dashboard');
    }
}
