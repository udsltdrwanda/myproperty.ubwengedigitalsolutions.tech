<div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between pb-2 mb-8 border-b border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900">Properties by District</h1>
        <a href="{{ route('landlord.land.adjacement') }}" class="text-blue-600 hover:underline">Adjacement</a>
    </div>

    @if ($propertiesByDistrict->isEmpty())
        <div class="p-6 text-center rounded-lg shadow-sm bg-gray-50">
            <p class="text-lg text-gray-500">No properties found in the database.</p>
            <p class="mt-2 text-gray-400">Properties will appear here once added to the system.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8">
            @foreach ($propertiesByDistrict as $districtData)
                <div class="overflow-hidden bg-white rounded-sm shadow-md">
                    <div class="flex items-center justify-between px-6 py-2 bg-blue-200">
                        <h2 class="text-xl font-semibold text-blue-700">
                            {{ $districtData['district_name'] }}
                        </h2>
                        <span class="px-3 py-1 text-sm font-medium text-white bg-blue-700 rounded-full">
                            {{ $districtData['property_count'] }} properties
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        @foreach ($districtData['properties'] as $property)
                            <table class="w-full mb-6 divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr class="bg-blue-300">
                                        <th colspan="8" class="px-4 py-4 text-left">
                                            <div>
                                                <strong>#{{ $loop->iteration }}</strong>
                                                <strong>UPI: {{ $property->upi }}</strong>
                                                {{ optional($property->sectorRelation)->name }},
                                                {{ optional($property->cellRelation)->name }},
                                                {{ optional($property->villageRelation)->name }},
                                                <strong>({{ $property->property_use }})</strong>,
                                                {{ $property->area }} Sqm,
                                                Registration Date
                                                {{ \Carbon\Carbon::parse($property->owned_year)->format('d M, Y') }}
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Year</th>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Area</th>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Land Value</th>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Building Value
                                        </th>

                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Land Tax</th>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Building Tax
                                            Rate</th>
                                        <th class="px-6 py-3 text-xs text-left text-gray-500 uppercase">Total Tax</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ now()->year }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format($property->area) }} m²</td>
                                        {{-- <td class="px-6 py-4 text-sm text-gray-700">{{ $property->land_tax_rate }}%</td> --}}
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format($property->land_value) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format($property->building_value) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format($property->value_per_m * $property->area) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format(num: $property->building_tax) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ number_format(num: $property->building_tax + $property->value_per_m * $property->area) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
