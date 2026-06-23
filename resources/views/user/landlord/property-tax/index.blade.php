<x-app-layout>
    <!-- Branded Header -->
    <div class="mb-6 p-4 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-hand-holding-usd text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-uds-navy uppercase tracking-wide">Property Tax</h1>
                    <p class="text-[10px] text-slate-400 font-semibold">Land & building tax assessment by district · {{ now()->format('Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('landlord.land.adjacement') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl border transition duration-150"
                   style="color:#003b70; border-color:#003b70; background:#fff;"
                   onmouseover="this.style.background='rgba(0,59,112,0.05)'" onmouseout="this.style.background='#fff'">
                    <i class="fas fa-sliders-h text-[10px]"></i> Adjacement Values
                </a>
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Report</p>
                    <p class="text-xs font-semibold text-slate-600">Tax Assessment</p>
                </div>
            </div>
        </div>
    </div>

    @livewire('user.land-lord.property-tax.property-tax-livewire')
</x-app-layout>
