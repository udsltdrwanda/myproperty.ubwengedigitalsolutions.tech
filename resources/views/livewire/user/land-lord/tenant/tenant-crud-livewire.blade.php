<div>
    <!-- ── Toolbar ── -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 mb-4 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl bg-slate-100 text-slate-600">
                <i class="fas fa-users text-[9px]"></i>
                {{ count($tenants) }} Client(s)
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="create"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-xl transition duration-150 shadow-md"
                style="background:linear-gradient(135deg,#003b70,#0b2545);">
                <i class="fas fa-plus text-[10px]"></i> Add Client
            </button>
            <button wire:click="exportAllToPdf"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl border transition duration-150"
                style="color:#003b70; border-color:#003b70; background:#fff;"
                onmouseover="this.style.background='rgba(0,59,112,0.05)'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-file-pdf text-[10px]"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- ── Desktop Table ── -->
    <div class="hidden md:block bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">#</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Client</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">ID / TIN</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Company</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Phone</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Email</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($tenants as $i => $tenant)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="w-6 h-6 rounded-lg inline-flex items-center justify-center text-[10px] font-extrabold"
                                  style="background:rgba(0,59,112,0.08); color:#003b70;">{{ $i + 1 }}</span>
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-extrabold shrink-0 text-white"
                                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                                    {{ strtoupper(substr($tenant->tenant_name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-extrabold text-uds-navy">{{ $tenant->tenant_name }}</span>
                            </div>
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-lg">
                                    <i class="fas fa-id-card text-[9px]"></i> {{ $tenant->tenant_id }}
                                </span>
                                @if($tenant->company_tin)
                                    <span class="text-[10px] text-slate-400 font-semibold pl-1">TIN: {{ $tenant->company_tin }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            @if($tenant->company_name)
                                <div class="flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0"
                                          style="background:rgba(243,146,0,0.1);">
                                        <i class="fas fa-building text-[9px]" style="color:#f39200;"></i>
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700">{{ $tenant->company_name }}</span>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-300 italic">—</span>
                            @endif
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            <a href="https://wa.me/{{ $tenant->phone }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition">
                                <i class="fab fa-whatsapp text-sm text-emerald-500"></i>
                                {{ $tenant->phone }}
                            </a>
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            <a href="mailto:{{ $tenant->email }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-uds-blue hover:underline transition">
                                <i class="fas fa-envelope text-[10px] text-slate-400"></i>
                                {{ $tenant->email }}
                            </a>
                        </td>

                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="edit({{ $tenant->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150 text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button wire:click="confirmDelete({{ $tenant->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150 text-red-600 bg-red-50 border-red-200 hover:bg-red-100">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-14 text-center">
                            <i class="fas fa-user-friends text-3xl mb-3 block text-slate-200"></i>
                            <p class="text-sm font-semibold text-slate-400">No clients found</p>
                            <button wire:click="create" class="mt-3 text-xs font-bold text-uds-blue hover:underline">Add your first client</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ── Mobile Cards ── -->
    <div class="block md:hidden space-y-3">
        @forelse ($tenants as $tenant)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-[0_4px_20px_rgb(0,0,0,0.03)] space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-extrabold shrink-0 text-white"
                         style="background:linear-gradient(135deg,#003b70,#0b2545);">
                        {{ strtoupper(substr($tenant->tenant_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-uds-navy truncate">{{ $tenant->tenant_name }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold truncate">{{ $tenant->company_name ?? 'Individual' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 bg-slate-50 rounded-xl">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Phone</p>
                        <a href="https://wa.me/{{ $tenant->phone }}" class="font-bold text-emerald-700">{{ $tenant->phone }}</a>
                    </div>
                    <div class="p-2.5 bg-slate-50 rounded-xl">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">ID</p>
                        <p class="font-bold text-slate-700 truncate">{{ $tenant->tenant_id }}</p>
                    </div>
                </div>
                <p class="text-[10px] text-uds-blue truncate">{{ $tenant->email }}</p>
                <div class="flex gap-2 pt-1 border-t border-slate-50">
                    <button wire:click="edit({{ $tenant->id }})"
                        class="flex-1 inline-flex items-center justify-center gap-1 py-1.5 text-[10px] font-bold rounded-xl border text-emerald-700 bg-emerald-50 border-emerald-200">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button wire:click="confirmDelete({{ $tenant->id }})"
                        class="flex-1 inline-flex items-center justify-center gap-1 py-1.5 text-[10px] font-bold rounded-xl border text-red-600 bg-red-50 border-red-200">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center">
                <i class="fas fa-user-friends text-2xl mb-2 block text-slate-200"></i>
                <p class="text-sm font-semibold text-slate-400">No clients yet</p>
            </div>
        @endforelse
    </div>

    @include('livewire.user.land-lord.tenant.create-tenant-livewire')
</div>
