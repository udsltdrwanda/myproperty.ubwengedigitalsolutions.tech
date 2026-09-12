<div class="space-y-5 pb-8">

    <div class="flex flex-wrap items-end justify-between gap-3 px-5 py-4 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
        <div>
            <label for="property_tax_year_filter" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter by Year</label>
            <select wire:model.live="year" id="property_tax_year_filter"
                class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 min-w-[120px]">
                @foreach ($availableYears as $availableYear)
                    <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                @endforeach
            </select>
        </div>
        <p class="text-[10px] font-semibold text-slate-400 pb-1">
            Showing land and building tax for <span class="font-extrabold text-uds-navy">{{ $year }}</span>
        </p>
    </div>

    @unless ($hasAdjacementForYear)
        <div class="px-5 py-4 bg-orange-50 border border-orange-100 rounded-2xl">
            <p class="text-xs font-extrabold text-uds-navy">No adjacement values for {{ $year }}</p>
            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">
                Property tax uses land and building assessment values, not invoices.
                Your 2023 invoices appear on
                <a href="{{ route('landlord.rental-income-tax') }}" class="font-bold text-uds-orange hover:underline">Rental Income Tax</a>.
                To see amounts here, add {{ $year }} values under Adjacement.
            </p>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="{{ route('landlord.land.adjacement') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-white rounded-xl"
                   style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-sliders-h text-[10px]"></i> Land Adjacement
                </a>
                <a href="{{ route('landlord.building.adjacement') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold rounded-xl border"
                   style="color:#003b70; border-color:#003b70;">
                    Building Adjacement
                </a>
            </div>
        </div>
    @endunless

    @if ($propertiesByDistrict->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-14 text-center shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(0,59,112,0.06);">
                <i class="fas fa-building text-2xl" style="color:#cbd5e1;"></i>
            </div>
            <p class="text-sm font-extrabold text-slate-500">No properties found</p>
            <p class="text-xs text-slate-400 mt-1">Add properties and adjacement values to see tax assessments for {{ $year }}.</p>
            <a href="{{ route('landlord.land.adjacement') }}"
               class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 text-xs font-bold text-white rounded-xl shadow-md"
               style="background:linear-gradient(135deg,#003b70,#0b2545);">
                <i class="fas fa-sliders-h text-[10px]"></i> Set Adjacement Values
            </a>
        </div>

    @else
        @foreach ($propertiesByDistrict as $districtData)
            @php
                $totalLandTax     = $districtData['properties']->sum(fn($p) => $p->value_per_m * $p->area);
                $totalBuildingTax = $districtData['properties']->sum(fn($p) => $p->building_tax);
                $grandTotal       = $totalLandTax + $totalBuildingTax;
                $totalLandValue   = $districtData['properties']->sum(fn($p) => $p->land_value);
                $totalBldgValue   = $districtData['properties']->sum(fn($p) => $p->building_value);
            @endphp

            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">

                {{-- District Header --}}
                <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4"
                     style="background:linear-gradient(135deg,#003b70 0%,#0b2545 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                             style="background:rgba(243,146,0,0.2); border:1px solid rgba(243,146,0,0.3);">
                            <i class="fas fa-map-marker-alt text-sm" style="color:#f39200;"></i>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-white">{{ $districtData['district_name'] }}</p>
                            <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.5);">
                                {{ $districtData['property_count'] }} {{ Str::plural('property', $districtData['property_count']) }}
                            </p>
                        </div>
                    </div>
                    {{-- District summary stats --}}
                    <div class="flex flex-wrap gap-4">
                        @foreach([
                            ['Land Value',     number_format($totalLandValue,0).' RWF',   '#60a5fa'],
                            ['Building Value', number_format($totalBldgValue,0).' RWF',   '#34d399'],
                            ['Land Tax',       number_format($totalLandTax,0).' RWF',     '#f39200'],
                            ['Building Tax',   number_format($totalBuildingTax,0).' RWF', '#f87171'],
                            ['Total Tax',      number_format($grandTotal,0).' RWF',       '#fbbf24'],
                        ] as [$lbl,$val,$col])
                            <div class="text-right">
                                <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.4);">{{ $lbl }}</p>
                                <p class="text-xs font-extrabold" style="color:{{ $col }};">{{ $val }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Properties --}}
                @foreach ($districtData['properties'] as $property)
                    @php
                        $landTax  = $property->value_per_m * $property->area;
                        $totalTax = $property->building_tax + $landTax;
                    @endphp

                    {{-- Property sub-header --}}
                    <div class="px-5 py-3 flex flex-wrap items-center gap-3 border-b border-slate-50"
                         style="background:#f8fafc;">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-extrabold rounded-xl"
                              style="background:rgba(0,59,112,0.08); color:#003b70;">
                            #{{ $loop->iteration }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold bg-slate-100 text-slate-600 rounded-xl">
                            <i class="fas fa-map-pin text-[9px]"></i> UPI: {{ $property->upi }}
                        </span>
                        @if(optional($property->sectorRelation)->name)
                            <span class="text-[10px] font-semibold text-slate-500">
                                {{ optional($property->sectorRelation)->name }},
                                {{ optional($property->cellRelation)->name }},
                                {{ optional($property->villageRelation)->name }}
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-xl"
                              style="background:rgba(243,146,0,0.08); color:#f39200;">
                            {{ $property->property_use }}
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400">{{ number_format($property->area) }} m²</span>
                        <span class="text-[10px] font-semibold text-slate-400">
                            Registered: {{ \Carbon\Carbon::parse($property->owned_year)->format('d M, Y') }}
                        </span>
                    </div>

                    {{-- Desktop table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    @foreach(['Year','Area (m²)','Land Value','Building Value','Land Tax','Building Tax','Total Tax'] as $th)
                                        <th class="py-3 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">{{ $th }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="transition duration-150"
                                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-extrabold rounded-xl"
                                              style="background:rgba(0,59,112,0.08); color:#003b70;">{{ $year }}</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap text-xs font-semibold text-slate-700">
                                        {{ number_format($property->area) }} m²
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="text-xs font-bold text-slate-800">{{ number_format($property->land_value) }}</span>
                                        <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="text-xs font-bold text-slate-800">{{ number_format($property->building_value) }}</span>
                                        <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="text-xs font-bold" style="color:#f39200;">{{ number_format($landTax) }}</span>
                                        <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="text-xs font-bold" style="color:#f87171;">{{ number_format($property->building_tax) }}</span>
                                        <span class="text-[10px] text-slate-400 ml-0.5">RWF</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-extrabold rounded-xl"
                                              style="background:rgba(220,38,38,0.08); color:#dc2626;">
                                            {{ number_format($totalTax) }} RWF
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile card --}}
                    <div class="block md:hidden p-4 border-b border-slate-50">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['Area',          number_format($property->area).' m²',           '#1e293b'],
                                ['Land Value',    number_format($property->land_value).' RWF',    '#1e293b'],
                                ['Building Val.', number_format($property->building_value).' RWF','#1e293b'],
                                ['Land Tax',      number_format($landTax).' RWF',                '#f39200'],
                                ['Building Tax',  number_format($property->building_tax).' RWF', '#f87171'],
                                ['Total Tax',     number_format($totalTax).' RWF',               '#dc2626'],
                            ] as [$lbl,$val,$col])
                                <div class="p-3 rounded-xl" style="background:#f8fafc;">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $lbl }}</p>
                                    <p class="text-xs font-extrabold mt-0.5" style="color:{{ $col }};">{{ $val }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(!$loop->last)
                        <div class="border-b border-dashed border-slate-100 mx-5"></div>
                    @endif
                @endforeach

                {{-- District totals footer --}}
                <div class="flex flex-wrap items-center justify-end gap-4 px-5 py-3 border-t border-slate-100" style="background:#f8fafc;">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-auto">District Totals</span>
                    <div class="text-right">
                        <p class="text-[10px] font-semibold text-slate-400">Land Tax</p>
                        <p class="text-xs font-extrabold" style="color:#f39200;">{{ number_format($totalLandTax,0) }} RWF</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-semibold text-slate-400">Building Tax</p>
                        <p class="text-xs font-extrabold" style="color:#f87171;">{{ number_format($totalBuildingTax,0) }} RWF</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-semibold text-slate-400">Total Tax</p>
                        <p class="text-xs font-extrabold" style="color:#dc2626;">{{ number_format($grandTotal,0) }} RWF</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>
