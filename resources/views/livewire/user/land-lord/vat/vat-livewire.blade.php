<div class="space-y-5 pb-8">

    <div class="flex flex-wrap items-end justify-between gap-3 px-5 py-4 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
        <div>
            <label for="vat_year_filter" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter by Year</label>
            <select wire:model.live="year" id="vat_year_filter"
                class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 min-w-[120px]">
                @foreach ($availableYears as $availableYear)
                    <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="exportToCsv"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-xl transition duration-150 shadow-md"
            style="background:linear-gradient(135deg,#059669,#047857);">
            <i class="fas fa-file-csv text-[10px]"></i>
            Export CSV
            <span wire:loading wire:target="exportToCsv"><i class="fas fa-spinner fa-spin ml-1 text-[10px]"></i></span>
        </button>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label'=>'Rental Amount','value'=>'RWF '.number_format($totalAmount,0),'sub'=>'excluding VAT','icon'=>'fa-file-invoice-dollar','color'=>'#003b70','light'=>'rgba(0,59,112,0.08)'],
                ['label'=>'Total VAT (18%)','value'=>'RWF '.number_format($totalVat,0),'sub'=>'VAT on invoices','icon'=>'fa-receipt','color'=>'#f39200','light'=>'rgba(243,146,0,0.08)'],
                ['label'=>'Total Billed','value'=>'RWF '.number_format($totalBilled,0),'sub'=>'amount + VAT','icon'=>'fa-coins','color'=>'#059669','light'=>'rgba(5,150,105,0.08)'],
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
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $k['light'] }}">
                        <i class="fas {{ $k['icon'] }} text-sm" style="color:{{ $k['color'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @forelse($byDistrict as $districtName => $districtRows)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-start justify-between gap-3"
                 style="background:linear-gradient(135deg,#003b70 0%,#0b2545 100%);">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                         style="background:rgba(243,146,0,0.2); border:1px solid rgba(243,146,0,0.3);">
                        <i class="fas fa-map-marker-alt text-sm" style="color:#f39200;"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-white">{{ $districtName }}</p>
                        <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.5);">District VAT report</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    @foreach([
                        ['Rental Amount', number_format($districtRows->sum('amount'),0).' RWF', '#60a5fa'],
                        ['VAT',           number_format($districtRows->sum('vat'),0).' RWF',    '#f39200'],
                        ['Total Billed',  number_format($districtRows->sum('total'),0).' RWF',  '#34d399'],
                    ] as [$lbl,$val,$col])
                        <div class="text-right">
                            <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.45);">{{ $lbl }}</p>
                            <p class="text-xs font-extrabold" style="color:{{ $col }};">{{ $val }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9;">
                            @foreach(['Property','UPI','House','Invoices','Rental Amount','VAT (18%)','Total Billed'] as $th)
                                <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($districtRows as $row)
                            <tr class="border-b border-slate-50" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <p class="text-xs font-extrabold text-uds-navy">{{ $row['property_name'] }}</p>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-lg">
                                        <i class="fas fa-map-pin text-[9px]"></i> {{ $row['property_upi'] ?: '—' }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-700">{{ $row['house_name'] }}</span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-xl"
                                          style="background:rgba(0,59,112,0.08); color:#003b70;">
                                        {{ $row['invoices_count'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-extrabold text-slate-800">{{ number_format($row['amount'],0) }}</span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($row['vat'],0) }}</span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="text-xs font-extrabold" style="color:#059669;">{{ number_format($row['total'],0) }}</span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc; border-top:2px solid #f1f5f9;">
                            <td colspan="4" class="py-3 px-5 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider text-right">District Totals</td>
                            <td class="py-3 px-5"><span class="text-xs font-extrabold" style="color:#003b70;">{{ number_format($districtRows->sum('amount'),0) }} RWF</span></td>
                            <td class="py-3 px-5"><span class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($districtRows->sum('vat'),0) }} RWF</span></td>
                            <td class="py-3 px-5"><span class="text-xs font-extrabold" style="color:#059669;">{{ number_format($districtRows->sum('total'),0) }} RWF</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="block md:hidden divide-y divide-slate-50">
                @foreach($districtRows as $row)
                    <div class="p-4 space-y-2">
                        <p class="text-xs font-extrabold text-uds-navy">{{ $row['property_name'] }}</p>
                        <p class="text-[10px] text-slate-400 font-semibold">{{ $row['house_name'] }} · {{ $row['invoices_count'] }} invoices</p>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="p-2.5 rounded-xl" style="background:#f8fafc;">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Amount</p>
                                <p class="text-xs font-extrabold text-slate-800">{{ number_format($row['amount'],0) }}</p>
                            </div>
                            <div class="p-2.5 rounded-xl" style="background:rgba(243,146,0,0.05);">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">VAT</p>
                                <p class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($row['vat'],0) }}</p>
                            </div>
                            <div class="p-2.5 rounded-xl" style="background:rgba(5,150,105,0.05);">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Total</p>
                                <p class="text-xs font-extrabold" style="color:#059669;">{{ number_format($row['total'],0) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-100 p-14 text-center shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(243,146,0,0.08);">
                <i class="fas fa-receipt text-2xl" style="color:#f39200;"></i>
            </div>
            <p class="text-sm font-extrabold text-slate-500">No VAT invoices found for {{ $year }}</p>
            <p class="text-xs text-slate-400 mt-1">Try another year, or record invoices with VAT for this period.</p>
        </div>
    @endforelse

</div>
