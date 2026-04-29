<?php

namespace App\Http\Controllers\User\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRequestController extends Controller
{
    public function index()
    {
        return view('user.admin.user-quest');
    }
}
