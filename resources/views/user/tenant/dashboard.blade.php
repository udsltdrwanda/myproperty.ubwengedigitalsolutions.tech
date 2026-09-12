<x-app-layout>
    <div class="mb-6 p-5 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                 style="background:linear-gradient(135deg,#003b70,#0b2545);">
                <i class="fas fa-home text-white"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">My Account</p>
                <h1 class="text-lg font-extrabold text-uds-navy">Welcome, {{ $tenant->tenant_name ?? $user->name }}</h1>
                <p class="text-xs font-medium text-slate-400 mt-0.5">Lease and billing summary · {{ now()->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Total billed', number_format($billed).' RWF', '#003b70'],
            ['VAT', number_format($vat).' RWF', '#f39200'],
            ['Paid', number_format($paid).' RWF', '#2d9d3f'],
            ['Balance due', number_format($balance).' RWF', '#dc2626'],
        ] as [$label, $value, $color])
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                <p class="text-lg font-extrabold mt-1" style="color:{{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <h3 class="text-sm font-extrabold text-uds-navy mb-3">My Contracts</h3>
            <p class="text-[10px] font-semibold text-slate-400 mb-4">{{ $activeContracts }} active</p>
            <div class="space-y-2">
                @forelse($contracts as $record)
                    <div class="p-3 rounded-xl" style="background:#f8fafc;">
                        <p class="text-xs font-extrabold text-uds-navy">{{ optional($record->unit->house)->name ?? 'Unit' }}</p>
                        <p class="text-[10px] font-semibold text-slate-400">
                            {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('d M Y') : '—' }}
                            –
                            {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('d M Y') : '—' }}
                        </p>
                    </div>
                @empty
                    <p class="text-xs font-semibold text-slate-400 py-6 text-center">No contracts on file.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <h3 class="text-sm font-extrabold text-uds-navy mb-4">Recent Invoices</h3>
            <div class="space-y-2">
                @forelse($invoices as $invoice)
                    <div class="flex items-center justify-between p-3 rounded-xl" style="background:#f8fafc;">
                        <div>
                            <p class="text-xs font-extrabold text-uds-navy">{{ $invoice->invoice_no }}</p>
                            <p class="text-[10px] font-semibold text-slate-400">{{ $invoice->invoice_status }}</p>
                        </div>
                        <p class="text-xs font-extrabold text-uds-navy">{{ number_format($invoice->amount + $invoice->vat) }} RWF</p>
                    </div>
                @empty
                    <p class="text-xs font-semibold text-slate-400 py-6 text-center">No invoices yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
