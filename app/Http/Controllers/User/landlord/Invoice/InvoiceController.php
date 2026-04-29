<?php

namespace App\Http\Controllers\User\Landlord\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('user.landlord.invoice.index');
    }

    public function show($invoice)
    {
        return view('user.landlord.invoice.show', ['invoiceId' => $invoice]);
    }
}
