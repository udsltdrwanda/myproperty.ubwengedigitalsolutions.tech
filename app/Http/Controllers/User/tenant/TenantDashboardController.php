<?php

namespace App\Http\Controllers\User\tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RentRecord;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        $invoices = collect();
        $contracts = collect();
        $billed = 0;
        $vat = 0;
        $paid = 0;

        if ($tenant) {
            $invoices = Invoice::with('payments')->where('tenant_id', $tenant->id)->latest()->get();
            $contracts = RentRecord::with('unit.house')->where('tenant_id', $tenant->id)->latest()->get();
            $billed = (float) $invoices->sum(fn ($invoice) => (float) $invoice->amount + (float) $invoice->vat);
            $vat = (float) $invoices->sum('vat');
            $paid = (float) $invoices->flatMap->payments->sum('payed_amount');
        }

        return view('user.tenant.dashboard', [
            'user' => $user,
            'tenant' => $tenant,
            'invoices' => $invoices->take(6),
            'contracts' => $contracts,
            'billed' => $billed,
            'vat' => $vat,
            'paid' => $paid,
            'balance' => max(0, $billed - $paid),
            'activeContracts' => $contracts->filter(fn ($record) => $record->end_date && now()->lte($record->end_date))->count(),
        ]);
    }
}
