<div class="container mx-auto">
    <div class="overflow-hidden bg-white shadow-lg rounded-xl">

        <!-- Back button -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <a href="{{ url()->previous() }}"
                class="flex items-center text-blue-600 transition-colors duration-300 hover:text-blue-800">
                <i class="mr-3 fas fa-arrow-left"></i>
                <span class="font-medium">Back to Properties</span>
            </a>
        </div>

        <!-- Property header -->
        <div class="px-6 py-6 bg-gradient-to-r from-blue-50 to-white">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">{{ $property->upi }}</h1>
            <h2 class="mb-1 text-2xl font-semibold text-gray-700">{{ $property->name }}</h2>
            <p class="text-sm tracking-wide text-gray-500 uppercase">{{ $property->type }} -
                {{ $property->property_use }}</p>
        </div>

        <!-- Property details -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Description -->
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="flex items-center mb-3 text-lg font-semibold text-gray-700">
                        <i class="mr-3 text-blue-500 fas fa-info-circle"></i>
                        Description
                    </h2>
                    <p class="text-gray-600">{{ $property->description }}</p>
                    <p class="mt-2 text-gray-600"><strong>Area:</strong> {{ $property->area }} m²</p>
                    <p class="text-gray-600"><strong>Owned Year:</strong>
                        {{ \Carbon\Carbon::parse($property->owned_year)->format('m-d-Y') }} </p>
                </div>

                <!-- Location -->
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="flex items-center mb-3 text-lg font-semibold text-gray-700">
                        <i class="mr-3 text-green-500 fas fa-map-marker-alt"></i>
                        Location
                    </h2>
                    <p class="text-gray-600">
                        {{ optional($property->villageRelation)->name }},
                        {{ optional($property->cellRelation)->name }},
                        {{ optional($property->sectorRelation)->name }},
                        {{ optional($property->districtRelation)->name }},
                        {{ optional($property->provinceRelation)->name }},
                        {{ $property->country }}
                    </p>
                </div>
            </div>

            <!-- Houses & Units -->
            <div class="mt-10">
                <h2 class="flex items-center mb-6 text-2xl font-bold text-gray-800">
                    <i class="mr-3 text-indigo-500 fas fa-building"></i>
                    Houses ({{ $property->houses->count() }})
                </h2>

                @foreach ($property->houses as $house)
                    <div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-2 text-xl font-semibold text-gray-700">{{ $house->name }} - Floor
                            {{ $house->floor }}</h3>
                        <p class="mb-4 text-gray-600">{{ $house->description }}</p>

                        <!-- Units under this house -->
                        <h4 class="mb-3 text-lg font-semibold text-gray-800">Units ({{ $house->units->count() }})</h4>
                        @forelse($house->units as $unit)
                            <div class="p-4 mb-4 transition-shadow border border-gray-100 rounded-lg hover:shadow-md">
                                <div class="flex flex-col items-start justify-between mb-3 md:flex-row md:items-center">
                                    <div>
                                        <h5 class="font-bold text-gray-800 text-md">{{ $unit->name }}</h5>
                                        <p class="text-sm text-gray-500">Room #{{ $unit->roomNumber }} | Floor
                                            {{ $unit->floor }}</p>
                                    </div>
                                    <span
                                        class="px-3 py-1 mt-2 md:mt-0 text-sm font-semibold text-white
                                        {{ $unit->unit_status === 'Available' ? 'bg-green-500' : 'bg-red-500' }} rounded-full">
                                        {{ $unit->unit_status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
                                    <div>
                                        <span class="text-gray-500">Type:</span>
                                        <p>{{ $unit->type }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Rent:</span>
                                        <p>{{ number_format($unit->rent) }} {{ ucfirst($unit->rentTypes) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Notes:</span>
                                        <p>{{ $unit->notes ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-span-3">
                                        <span class="text-gray-500">Amenities:</span>
                                        <p>
                                            @if (is_array($unit->amenities) && count($unit->amenities))
                                                {{ implode(', ', $unit->amenities) }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm italic text-gray-400">No units found in this house.</p>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
