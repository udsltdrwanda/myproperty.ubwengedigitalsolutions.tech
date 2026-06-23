<x-app-layout>
    <!-- Branded Header (same pattern as /landlord/property) -->
    <div class="mb-6 p-4 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-percent text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-uds-navy uppercase tracking-wide">Rental Income Tax</h1>
                    <p class="text-[10px] text-slate-400 font-semibold">Annual progressive tax report by district · {{ now()->format('Y') }}</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Report</p>
                <p class="text-xs font-semibold text-slate-600">Tax Analysis</p>
            </div>
        </div>
    </div>

    @livewire('user.land-lord.rental-income-tax.rental-income-tax-livewire')
</x-app-layout>
