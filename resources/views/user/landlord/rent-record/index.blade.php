<x-app-layout>
    <!-- Header Title Card -->
    <div class="mb-6 p-6 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-slate-50/50 to-transparent">
        <div>
            <h1 class="text-xl font-extrabold text-uds-navy uppercase tracking-wide">Rent Agreements & Records</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Manage tenant lease contracts, rent schedules, and generate invoices.</p>
        </div>
        <div class="text-left md:text-right">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold text-uds-blue bg-blue-50 border border-blue-100 rounded-lg">
                <i class="fas fa-file-contract"></i>
                <span>LEASE LEDGER</span>
            </span>
        </div>
    </div>

    <!-- Livewire Content Container -->
    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        @livewire('user.land-lord.rent-record.rent-record-livewire')
    </div>
</x-app-layout>
