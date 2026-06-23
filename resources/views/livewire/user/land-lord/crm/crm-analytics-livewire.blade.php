<div class="min-h-screen" style="background:#f8fafc;">

    <!-- ═══ Hero Header ═══ -->
    <div class="mb-6 border-b" style="background:#ffffff; border-color:#f1f5f9;">
        <div class="px-6 py-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:linear-gradient(135deg,#003b70,#0b2545);">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-wide" style="color:#0b2545;">CRM Analytics</h1>
                        <p class="text-xs font-semibold mt-0.5" style="color:#94a3b8;">Client billing performance, lease history & payment analysis</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider" style="color:#94a3b8;">
                    <i class="fas fa-home text-[9px]"></i><span>Report</span>
                    <i class="fas fa-chevron-right text-[8px]"></i>
                    <span style="color:#f39200;">CRM Analytics</span>
                </div>
            </div>

            <!-- KPI Cards inside hero -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                @php
                    $collRate = $totalRevenue > 0 ? round(($totalCollected/$totalRevenue)*100) : 0;
                    $kpis = [
                        ['label'=>'Clients Shown',    'value'=> $tenantProfiles->count(),                   'sub'=>'of all clients',          'icon'=>'fa-users',              'accent'=>'#60a5fa'],
                        ['label'=>'Active Tenants',   'value'=> $activeTenantsCount,                        'sub'=>'with live contracts',     'icon'=>'fa-user-check',         'accent'=>'#34d399'],
                        ['label'=>'Total Billed',     'value'=>'RWF '.number_format($totalRevenue,0),       'sub'=>'Collection: '.$collRate.'%','icon'=>'fa-file-invoice-dollar','accent'=>'#f39200'],
                        ['label'=>'Outstanding',      'value'=>'RWF '.number_format($totalOutstanding,0),   'sub'=>'unpaid balance',          'icon'=>'fa-exclamation-circle', 'accent'=>'#f87171'],
                    ];
                @endphp
                @foreach($kpis as $kpi)
                    <div class="rounded-2xl p-4" style="background:#f8fafc; border:1px solid #f1f5f9;">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#94a3b8;">{{ $kpi['label'] }}</p>
                                <p class="text-xl font-extrabold truncate" style="color:{{ $kpi['accent'] }}">{{ $kpi['value'] }}</p>
                                <p class="text-[10px] font-semibold mt-0.5" style="color:#94a3b8;">{{ $kpi['sub'] }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:#fff; border:1px solid #f1f5f9;">
                                <i class="fas {{ $kpi['icon'] }} text-sm" style="color:{{ $kpi['accent'] }}"></i>
                            </div>
                        </div>
                        @if($kpi['icon']==='fa-file-invoice-dollar' && $totalRevenue > 0)
                            <div class="mt-3">
                                <div class="w-full rounded-full h-1.5" style="background:#e2e8f0;">
                                    <div class="h-1.5 rounded-full transition-all" style="width:{{ $collRate }}%; background:#f39200;"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="px-4 pb-10 space-y-5">

        <!-- ═══ Filter Panel ═══ -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2" style="background:#f8fafc;">
                <i class="fas fa-sliders-h text-xs" style="color:#003b70;"></i>
                <span class="text-[11px] font-extrabold uppercase tracking-widest" style="color:#003b70;">Filters & Sorting</span>
                @if($search || $invoiceStatus || $contractStatus || $paymentRate || $houseFilter)
                    <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-full" style="background:rgba(243,146,0,0.1); color:#f39200;">
                        <i class="fas fa-circle text-[6px]"></i> Active
                    </span>
                @endif
            </div>

            <div class="p-5 space-y-4">
                <!-- Row 1: Search + Dropdowns -->
                <div class="flex flex-wrap items-end gap-3">
                    <!-- Search -->
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1.5" style="color:#94a3b8;">Search Client</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Name, email, phone, company…"
                                class="w-full pl-9 pr-3 py-2.5 text-xs font-semibold border border-slate-200 rounded-xl transition duration-150"
                                style="color:#1e293b; background:#fff;"
                                onfocus="this.style.borderColor='#003b70';this.style.boxShadow='0 0 0 4px rgba(0,59,112,0.06)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                            @if($search)
                                <button wire:click="$set('search','')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-red-400 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    @php
                        $dropdowns = [
                            ['wire' => 'invoiceStatus',  'label' => 'Invoice Status',   'options' => ['' => 'All Statuses',   'Paid' => 'Paid', 'Partial' => 'Partial', 'Pending' => 'Pending', 'Canceled' => 'Canceled']],
                            ['wire' => 'contractStatus', 'label' => 'Contract Status',  'options' => ['' => 'All Contracts',  'active' => 'Active Only', 'expired' => 'Expired Only']],
                            ['wire' => 'paymentRate',    'label' => 'Payment Rate',     'options' => ['' => 'All Rates',      'high' => 'High ≥ 80%', 'medium' => 'Medium 50–79%', 'low' => 'Low < 50%']],
                        ];
                    @endphp

                    @foreach($dropdowns as $dd)
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider mb-1.5" style="color:#94a3b8;">{{ $dd['label'] }}</label>
                            <select wire:model.live="{{ $dd['wire'] }}"
                                class="px-3 py-2.5 text-xs font-semibold border border-slate-200 rounded-xl transition duration-150 appearance-none pr-8"
                                style="color:#1e293b; background:#fff; min-width:140px;"
                                onfocus="this.style.borderColor='#003b70'" onblur="this.style.borderColor='#e2e8f0'">
                                @foreach($dd['options'] as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

                    <!-- House -->
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-1.5" style="color:#94a3b8;">House / Property</label>
                        <select wire:model.live="houseFilter"
                            class="px-3 py-2.5 text-xs font-semibold border border-slate-200 rounded-xl transition duration-150"
                            style="color:#1e293b; background:#fff; min-width:140px;"
                            onfocus="this.style.borderColor='#003b70'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">All Houses</option>
                            @foreach($houses as $house)
                                <option value="{{ $house->id }}">{{ $house->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset -->
                    <button wire:click="resetFilters"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold border rounded-xl transition duration-150"
                        style="color:#64748b; background:#f8fafc; border-color:#e2e8f0;"
                        onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                        <i class="fas fa-undo text-[10px]"></i> Reset All
                    </button>
                </div>

                <!-- Row 2: Sort + Active chips -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider" style="color:#94a3b8;">Sort:</span>
                    @foreach(['total_billed'=>'Total Billed','payment_rate'=>'Pay Rate','name'=>'Name','contracts'=>'Contracts'] as $col=>$lbl)
                        <button wire:click="toggleSort('{{ $col }}')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition duration-150"
                            style="{{ $sortBy===$col ? 'background:#003b70;color:#fff;border-color:#003b70;' : 'background:#fff;color:#64748b;border-color:#e2e8f0;' }}">
                            {{ $lbl }}
                            @if($sortBy===$col)
                                <i class="fas fa-arrow-{{ $sortDir==='asc'?'up':'down' }} text-[9px]"></i>
                            @endif
                        </button>
                    @endforeach

                    <!-- Active chips -->
                    @foreach([
                        ['val'=>$search,         'label'=>'"'.$search.'"',        'clear'=>"search"],
                        ['val'=>$invoiceStatus,  'label'=>$invoiceStatus,          'clear'=>"invoiceStatus"],
                        ['val'=>$contractStatus, 'label'=>ucfirst($contractStatus),'clear'=>"contractStatus"],
                        ['val'=>$paymentRate,    'label'=>'Rate: '.ucfirst($paymentRate), 'clear'=>"paymentRate"],
                    ] as $chip)
                        @if($chip['val'])
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-full border" style="background:rgba(0,59,112,0.06);color:#003b70;border-color:rgba(0,59,112,0.15);">
                                {{ $chip['label'] }}
                                <button wire:click="$set('{{ $chip['clear'] }}','')" class="ml-0.5 hover:text-red-500">×</button>
                            </span>
                        @endif
                    @endforeach

                    <!-- Loading -->
                    <div wire:loading class="ml-auto flex items-center gap-1.5 text-[10px] font-bold" style="color:#003b70;">
                        <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Filtering…
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ Client Table ═══ -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- Table bar -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100" style="background:#f8fafc;">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(0,59,112,0.08);">
                        <i class="fas fa-users text-xs" style="color:#003b70;"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold" style="color:#0b2545;">Client History</h2>
                        <p class="text-[10px] font-semibold" style="color:#94a3b8;">Billing, contracts & payment rate per client</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 text-[10px] font-extrabold rounded-xl text-white" style="background:#003b70;">
                    {{ $tenantProfiles->count() }} Client(s)
                </span>
            </div>

            <!-- Desktop table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                            @foreach(['#','Client','Contact','Contracts','Invoices','Total Billed','Collected','Balance','Pay Rate','Invoice Mix','Last Contract'] as $th)
                                <th class="py-3 px-4 text-[10px] font-extrabold uppercase tracking-wider whitespace-nowrap" style="color:#94a3b8;">{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenantProfiles as $i => $p)
                            @php
                                $t    = $p['tenant'];
                                $rate = $p['payment_rate'];
                                $rc   = $rate>=80?'#059669':($rate>=50?'#f39200':'#dc2626');
                                $initials = strtoupper(substr($t->tenant_name??'?',0,1));
                            @endphp
                            <tr class="border-b border-slate-50 transition duration-150" style="border-bottom:1px solid #f8fafc;"
                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="w-6 h-6 rounded-lg inline-flex items-center justify-center text-[10px] font-extrabold" style="background:rgba(0,59,112,0.08);color:#003b70;">{{ $i+1 }}</span>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-extrabold shrink-0 text-white"
                                             style="background:linear-gradient(135deg,#003b70,#0b2545);">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold" style="color:#0b2545;">{{ $t->tenant_name }}</p>
                                            @if($t->company_name)
                                                <p class="text-[10px] font-semibold" style="color:#94a3b8;">{{ $t->company_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="space-y-0.5">
                                        @if($t->email)
                                            <p class="text-[10px] font-semibold flex items-center gap-1" style="color:#64748b;">
                                                <i class="fas fa-envelope text-slate-300 text-[9px]"></i> {{ $t->email }}
                                            </p>
                                        @endif
                                        @if($t->phone)
                                            <p class="text-[10px] font-semibold flex items-center gap-1" style="color:#64748b;">
                                                <i class="fas fa-phone text-slate-300 text-[9px]"></i> {{ $t->phone }}
                                            </p>
                                        @endif
                                        @if(!$t->email && !$t->phone)
                                            <span class="text-[10px] italic" style="color:#cbd5e1;">No contact</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="text-xs font-extrabold" style="color:#1e293b;">{{ $p['contracts'] }}</p>
                                    <p class="text-[10px] font-bold mt-0.5" style="color:{{ $p['active_contracts']>0?'#059669':'#94a3b8' }};">
                                        {{ $p['active_contracts']>0 ? $p['active_contracts'].' active' : 'expired' }}
                                    </p>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-xs font-extrabold" style="color:#1e293b;">{{ $p['invoices_count'] }}</span>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-xs font-extrabold" style="color:#1e293b;">{{ number_format($p['total_billed'],0) }}</span>
                                    <p class="text-[10px] font-semibold" style="color:#94a3b8;">RWF</p>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-xs font-bold" style="color:#059669;">{{ number_format($p['total_paid'],0) }}</span>
                                    <p class="text-[10px] font-semibold" style="color:#94a3b8;">RWF</p>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-xs font-bold" style="color:{{ $p['total_balance']>0?'#dc2626':'#94a3b8' }};">{{ number_format($p['total_balance'],0) }}</span>
                                    <p class="text-[10px] font-semibold" style="color:#94a3b8;">RWF</p>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 min-w-[90px]">
                                        <div class="flex-1 rounded-full h-2" style="background:#f1f5f9;">
                                            <div class="h-2 rounded-full transition-all" style="width:{{ $rate }}%; background:{{ $rc }};"></div>
                                        </div>
                                        <span class="text-[11px] font-extrabold shrink-0" style="color:{{ $rc }};">{{ $rate }}%</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(['Paid'=>['#dcfce7','#166534'],'Partial'=>['#dbeafe','#1d4ed8'],'Pending'=>['#fef9c3','#854d0e'],'Canceled'=>['#fee2e2','#991b1b']] as $st=>[$bg,$fg])
                                            @if(isset($p['invoice_statuses'][$st]))
                                                <span class="px-1.5 py-0.5 text-[9px] font-extrabold rounded-full" style="background:{{ $bg }};color:{{ $fg }};">
                                                    {{ $st[0] }}: {{ $p['invoice_statuses'][$st] }}
                                                </span>
                                            @endif
                                        @endforeach
                                        @if($p['invoices_count']===0)
                                            <span class="text-[10px] italic" style="color:#cbd5e1;">—</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($p['latest_record'])
                                        @php
                                            $lr = $p['latest_record'];
                                            $isActive = \Carbon\Carbon::parse($lr->end_date)->isFuture();
                                        @endphp
                                        <p class="text-[10px] font-semibold" style="color:#64748b;">
                                            {{ \Carbon\Carbon::parse($lr->start_date)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($lr->end_date)->format('d/m/Y') }}
                                        </p>
                                        <span class="text-[10px] font-bold" style="color:{{ $isActive?'#059669':'#94a3b8' }};">
                                            {{ $isActive ? '● Active' : '○ Expired' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] italic" style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-16 text-center">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(0,59,112,0.05);">
                                        <i class="fas fa-filter text-2xl" style="color:#cbd5e1;"></i>
                                    </div>
                                    <p class="text-sm font-extrabold" style="color:#94a3b8;">No clients match these filters</p>
                                    <button wire:click="resetFilters" class="mt-3 text-xs font-bold underline" style="color:#003b70;">Clear all filters</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="block md:hidden divide-y divide-slate-50">
                @forelse($tenantProfiles as $i => $p)
                    @php
                        $t    = $p['tenant'];
                        $rate = $p['payment_rate'];
                        $rc   = $rate>=80?'#059669':($rate>=50?'#f39200':'#dc2626');
                    @endphp
                    <div class="p-5 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-extrabold shrink-0 text-white"
                                 style="background:linear-gradient(135deg,#003b70,#0b2545);">
                                {{ strtoupper(substr($t->tenant_name??'?',0,1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-extrabold truncate" style="color:#0b2545;">{{ $t->tenant_name }}</p>
                                <p class="text-[10px] font-semibold truncate" style="color:#94a3b8;">{{ $t->email ?? $t->phone ?? '—' }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full" style="{{ $p['active_contracts']>0 ? 'background:#dcfce7;color:#166534;' : 'background:#f1f5f9;color:#94a3b8;' }}">
                                {{ $p['active_contracts']>0 ? 'Active' : 'Expired' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                ['Contracts', $p['contracts'].' ('.$p['active_contracts'].' active)', '#1e293b'],
                                ['Invoices',  $p['invoices_count'],                                   '#1e293b'],
                                ['Billed',    'RWF '.number_format($p['total_billed'],0),             '#1e293b'],
                                ['Balance',   'RWF '.number_format($p['total_balance'],0),            $p['total_balance']>0?'#dc2626':'#94a3b8'],
                            ] as [$lbl,$val,$col])
                                <div class="p-3 rounded-xl" style="background:#f8fafc;">
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5" style="color:#94a3b8;">{{ $lbl }}</p>
                                    <p class="text-xs font-extrabold" style="color:{{ $col }};">{{ $val }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <div class="flex justify-between text-[10px] font-bold mb-1.5">
                                <span style="color:#94a3b8;">Payment Rate</span>
                                <span style="color:{{ $rc }};">{{ $rate }}%</span>
                            </div>
                            <div class="w-full rounded-full h-2.5" style="background:#f1f5f9;">
                                <div class="h-2.5 rounded-full" style="width:{{ $rate }}%; background:{{ $rc }};"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i class="fas fa-filter text-2xl mb-3 block" style="color:#e2e8f0;"></i>
                        <p class="text-sm font-semibold" style="color:#94a3b8;">No clients match filters</p>
                        <button wire:click="resetFilters" class="mt-2 text-xs font-bold underline" style="color:#003b70;">Clear all</button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
