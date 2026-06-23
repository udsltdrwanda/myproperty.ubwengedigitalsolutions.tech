<?php

namespace App\Http\Controllers\User\landlord;

use App\Http\Controllers\Controller;

class CrmAnalyticsController extends Controller
{
    public function __invoke()
    {
        return view('user.landlord.crm.index');
    }
}
