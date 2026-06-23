<x-app-layout>
    <!-- Header Card (same pattern as /landlord/property) -->
    <div class="mb-6 p-4 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Icon + Title -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-user-friends text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-uds-navy uppercase tracking-wide">Client Management</h1>
                    <p class="text-[10px] text-slate-400 font-semibold">Manage tenants, contacts and company details</p>
                </div>
            </div>
            <!-- Context -->
            <div class="text-right hidden sm:block">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Users</p>
                <p class="text-xs font-semibold text-slate-600">Tenant Directory</p>
            </div>
        </div>
    </div>

    @livewire('user.land-lord.tenant.tenant-crud-livewire')
</x-app-layout>
