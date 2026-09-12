<x-app-layout>
    <div class="mb-6 p-5 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:linear-gradient(135deg,#003b70,#0b2545);">
                <i class="fas fa-briefcase text-white"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Operations</p>
                <h1 class="text-lg font-extrabold text-uds-navy">Welcome, {{ $user->name }}</h1>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Manager dashboard · {{ now()->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['Properties', $properties, 'fa-building', '#003b70'],
            ['Units', $units, 'fa-door-open', '#0b2545'],
            ['Clients', $tenants, 'fa-user-friends', '#2d9d3f'],
            ['Live contracts', $activeContracts, 'fa-file-signature', '#f39200'],
            ['Pending invoices', $pendingInvoices, 'fa-file-invoice-dollar', '#dc2626'],
        ] as [$label, $value, $icon, $color])
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                <p class="text-xl font-extrabold mt-1" style="color:{{ $color }}">{{ $value }}</p>
                <i class="fas {{ $icon }} text-xs mt-2" style="color:{{ $color }}"></i>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-extrabold text-uds-navy">Recent Invoices</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr style="background:#f8fafc;">
                        @foreach(['Invoice','Client','Amount','Status'] as $th)
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInvoices as $invoice)
                        <tr class="border-t border-slate-50">
                            <td class="py-3.5 px-4 text-xs font-extrabold text-uds-navy">{{ $invoice->invoice_no }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4 text-xs font-bold text-slate-700">{{ number_format($invoice->amount + $invoice->vat) }} RWF</td>
                            <td class="py-3.5 px-4 text-xs font-bold">{{ $invoice->invoice_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-xs font-semibold text-slate-400">No invoices yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
