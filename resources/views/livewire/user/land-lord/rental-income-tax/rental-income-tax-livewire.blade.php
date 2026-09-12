<div class="space-y-5 pb-8">

    {{-- ══ Toolbar ══ --}}
    <div class="flex flex-wrap items-end justify-between gap-3 px-5 py-4 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label for="tax_year_filter" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter by Year</label>
                <select wire:model.live="year" id="tax_year_filter"
                    class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 min-w-[120px]">
                    @foreach ($availableYears as $availableYear)
                        <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-wrap items-center gap-2 pb-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rwanda Tax Brackets:</span>
                @foreach([['0%','≤ 180K','#059669','rgba(5,150,105,0.08)'],['20%','180K–1M','#f39200','rgba(243,146,0,0.08)'],['30%','> 1M','#dc2626','rgba(220,38,38,0.08)']] as [$rate,$range,$fg,$bg])
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-extrabold rounded-xl border"
                          style="color:{{ $fg }}; background:{{ $bg }}; border-color:{{ $fg }}40;">
                        {{ $rate }} <span class="font-semibold opacity-70">{{ $range }}</span>
                    </span>
                @endforeach
                <span class="text-[10px] font-semibold text-slate-400 italic">· 50% of rental income is taxable · Tax is per district</span>
            </div>
        </div>
        <button wire:click="exportToCsv"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-xl transition duration-150 shadow-md"
            style="background:linear-gradient(135deg,#059669,#047857);">
            <i class="fas fa-file-csv text-[10px]"></i>
            Export CSV
            <span wire:loading wire:target="exportToCsv"><i class="fas fa-spinner fa-spin ml-1 text-[10px]"></i></span>
        </button>
    </div>

    {{-- ══ KPI Cards ══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label'=>'Total Rental Income','value'=>'RWF '.number_format($grandTotalIncome,0),'sub'=>'100% of invoiced','icon'=>'fa-file-invoice-dollar','color'=>'#003b70','light'=>'rgba(0,59,112,0.08)'],
                ['label'=>'Taxable Income (50%)','value'=>'RWF '.number_format($grandTotalIncome/2,0),'sub'=>'applied tax base','icon'=>'fa-money-bill-wave','color'=>'#f39200','light'=>'rgba(243,146,0,0.08)'],
                ['label'=>'Total Annual Tax','value'=>'RWF '.number_format($grandTotalTax,0),'sub'=>'progressive rate','icon'=>'fa-hand-holding-usd','color'=>'#dc2626','light'=>'rgba(220,38,38,0.08)'],
                ['label'=>'Reporting Period','value'=>'Jan 1 – Dec 31','sub'=>(string) $year,'icon'=>'fa-calendar-alt','color'=>'#7c3aed','light'=>'rgba(124,58,237,0.08)'],
            ];
        @endphp
        @foreach($kpis as $k)
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $k['label'] }}</p>
                        <p class="text-lg font-extrabold mt-1 leading-tight" style="color:{{ $k['color'] }}">{{ $k['value'] }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ $k['sub'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:{{ $k['light'] }}">
                        <i class="fas {{ $k['icon'] }} text-sm" style="color:{{ $k['color'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══ Districts ══ --}}
    @forelse($districtsWithInvoices as $district)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">

            {{-- District header --}}
            <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-start justify-between gap-3"
                 style="background:linear-gradient(135deg,#003b70 0%,#0b2545 100%);">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                         style="background:rgba(243,146,0,0.2); border:1px solid rgba(243,146,0,0.3);">
                        <i class="fas fa-map-marker-alt text-sm" style="color:#f39200;"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-white">{{ $district['district_name'] }}</p>
                        <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.5);">District tax report</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach([
                        ['Rental Income (100%)', number_format($district['total_amount'],0).' RWF', '#60a5fa'],
                        ['Bank Interest',         number_format($district['bank_interest'],0).' RWF', '#34d399'],
                        ['Taxable (50% − Int.)',  number_format($district['taxable_amount'],0).' RWF', '#f39200'],
                        ['Annual Tax',            number_format($district['total_tax'],0).' RWF',      '#f87171'],
                    ] as [$lbl,$val,$col])
                        <div class="text-right">
                            <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.45);">{{ $lbl }}</p>
                            <p class="text-xs font-extrabold" style="color:{{ $col }};">{{ $val }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tax bracket breakdown --}}
            <div class="px-5 py-3 border-b border-slate-100 flex flex-wrap items-center gap-3" style="background:#f8fafc;">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bracket Breakdown:</span>
                @foreach([
                    ['0%',  $district['tax_breakdown']['first_bracket'],  '#059669', 'rgba(5,150,105,0.08)'],
                    ['20%', $district['tax_breakdown']['middle_bracket'],  '#f39200', 'rgba(243,146,0,0.08)'],
                    ['30%', $district['tax_breakdown']['upper_bracket'],   '#dc2626', 'rgba(220,38,38,0.08)'],
                ] as [$rate, $b, $col, $bg])
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-[10px] font-bold"
                         style="color:{{ $col }}; background:{{ $bg }}; border-color:{{ $col }}30;">
                        <span class="font-extrabold">{{ $rate }}</span>
                        <span class="font-semibold opacity-80">{{ number_format($b['amount'],0) }} RWF</span>
                        @if($b['tax'] > 0)
                            <span class="opacity-60">→ {{ number_format($b['tax'],0) }} tax</span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Houses table (desktop) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                            @foreach(['Property','UPI','House','Units','Rental Income (100%)','Taxable (50%)'] as $th)
                                <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($district['houses'] as $h)
                            <tr class="border-b border-slate-50 transition duration-150"
                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <p class="text-xs font-extrabold text-uds-navy">{{ $h['property_name'] }}</p>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-lg">
                                        <i class="fas fa-map-pin text-[9px]"></i> {{ $h['property_upi'] ?: '—' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-home text-[10px]" style="color:#003b70;"></i>
                                        <span class="text-xs font-semibold text-slate-700">{{ $h['house']['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-xl"
                                          style="background:rgba(0,59,112,0.08); color:#003b70;">
                                        {{ $h['units_count'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-extrabold text-slate-800">{{ number_format($h['invoice_amount'],0) }}</span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-bold" style="color:#f39200;">{{ number_format($h['invoice_amount']/2,0) }}</span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc; border-top:2px solid #f1f5f9;">
                            <td colspan="4" class="py-3 px-5 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider text-right">District Totals</td>
                            <td class="py-3 px-5 whitespace-nowrap">
                                <span class="text-xs font-extrabold" style="color:#003b70;">{{ number_format($district['total_amount'],0) }} RWF</span>
                            </td>
                            <td class="py-3 px-5 whitespace-nowrap">
                                <span class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($district['taxable_amount'],0) }} RWF</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Houses mobile cards --}}
            <div class="block md:hidden divide-y divide-slate-50">
                @foreach($district['houses'] as $h)
                    <div class="p-4 space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(0,59,112,0.08);">
                                <i class="fas fa-home text-xs" style="color:#003b70;"></i>
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-uds-navy">{{ $h['house']['name'] }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold">{{ $h['property_name'] }} · {{ $h['units_count'] }} units</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-2.5 rounded-xl" style="background:#f8fafc;">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Income (100%)</p>
                                <p class="text-xs font-extrabold text-slate-800">{{ number_format($h['invoice_amount'],0) }} RWF</p>
                            </div>
                            <div class="p-2.5 rounded-xl" style="background:rgba(243,146,0,0.05);">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Taxable (50%)</p>
                                <p class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($h['invoice_amount']/2,0) }} RWF</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-100 p-14 text-center shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(243,146,0,0.08);">
                <i class="fas fa-exclamation-triangle text-2xl" style="color:#f39200;"></i>
            </div>
            <p class="text-sm font-extrabold text-slate-500">No rental income data found for {{ $year }}</p>
            <p class="text-xs text-slate-400 mt-1">Try another year, or ensure invoices are recorded for this reporting period.</p>
        </div>
    @endforelse

</div>
