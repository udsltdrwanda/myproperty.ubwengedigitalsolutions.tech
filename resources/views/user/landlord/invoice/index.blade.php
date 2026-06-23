<x-app-layout>
    <!-- Premium Page Header -->
    <div class="px-6 py-5 mb-6 bg-white border-b border-slate-100 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <!-- Icon Badge -->
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                    style="background: linear-gradient(135deg, #003b70 0%, #0b2545 100%);">
                    <i class="fas fa-file-invoice-dollar text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-uds-navy uppercase tracking-wide">Invoice Ledger</h1>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Track, manage, and process all tenant invoices and billing records.</p>
                </div>
            </div>
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                <span>Landlord Portal</span>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-uds-orange">Invoices</span>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="px-4 pb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">
            @livewire('user.land-lord.invoice.invoice-crud-livewire')
        </div>
    </div>
</x-app-layout>
