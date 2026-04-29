<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Units Management</h2>
        <div class="flex items-center space-x-4">
            <button wire:click="loadAllUnits" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View All Units
            </button>
        </div>
    </div>

    <div class="p-4 mb-6 rounded-lg bg-gray-50">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Select Property (Land)</label>
                <select wire:model.live="selected_property_id" class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Properties</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->upi }} &nbsp;,&nbsp;{{ $property->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Select Building</label>
                <select wire:model.live="selected_house_id" class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    {{ empty($houses) && $selected_property_id ? 'disabled' : '' }}>
                    <option value="">All Buildings</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-4">
                <button wire:click="createUnit({{ $selected_property_id ? $selected_property_id : 'null' }}, {{ $selected_house_id ? $selected_house_id : 'null' }})"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    {{ empty($selected_house_id) ? 'disabled' : '' }}>
                    <i class="mr-2 fas fa-plus"></i> Add Unit
                </button>

                <a href="{{ route('landlord.import.unit.index') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    {{ empty($selected_house_id) ? 'disabled' : '' }}>
                    <i class="mr-2 fas fa-file-import"></i> Import Excel
                </a>
            </div>
        </div>
    </div>

    @if (count($units))
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if ($viewAllUnits || !$selected_house_id)
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Building</th>
                        @endif
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Room</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Rent (Rwf)</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Rental Types</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($units as $unit)
                        <tr class="hover:bg-gray-50">
                            @if ($viewAllUnits || !$selected_house_id)
                                <td class="px-6 py-4 whitespace-nowrap">{{ $unit->house->property->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $unit->house->name }}</td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">{{ $unit->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $unit->roomNumber }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($unit->rent) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($unit->rentTypes) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($unit->activeRentRecord)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Occupied
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $unit->unit_status ?? 'Available' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($unit->activeRentRecord)
                                    @php
                                        $endDate = \Carbon\Carbon::parse($unit->activeRentRecord->end_date);
                                        $now = \Carbon\Carbon::now();
                                        $diff = $now->diff($endDate);
                                    @endphp

                                    @if ($endDate->isPast())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $diff->m }} month{{ $diff->m !== 1 ? 's' : '' }} and
                                            {{ $diff->d }} day{{ $diff->d !== 1 ? 's' : '' }} left
                                        </span>
                                    @endif
                                @else
                                    <div class="flex space-x-2">
                                        <button wire:click="editUnit({{ $unit->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="confirmDeleteUnit({{ $unit->id }})"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 mt-4 bg-white border-t border-gray-200 sm:px-6">
            {{ $units->links() }}
        </div>
    @elseif ($selected_house_id)
        <div class="p-4 text-center text-gray-600 rounded-lg bg-gray-50">
            <i class="mb-2 text-4xl fas fa-building"></i>
            <p>No units available for this building.</p>
        </div>
    @elseif ($selected_property_id)
        <div class="p-4 text-center text-gray-600 rounded-lg bg-gray-50">
            <i class="mb-2 text-4xl fas fa-building"></i>
            <p>No units available for this property. Please select a building or add units.</p>
        </div>
    @else
        <div class="p-4 text-center text-gray-600 rounded-lg bg-gray-50">
            <i class="mb-2 text-4xl fas fa-building"></i>
            <p>No units available. Please select a property and building to add units.</p>
        </div>
    @endif

    {{-- Unit Modal --}}
    @if ($showUnitModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button wire:click="closeUnitModal" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                {{ $unit_id ? 'Edit Unit' : 'Create Unit' }}
                            </h3>

                            <form class="mt-6">
                                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Property</label>
                                        <select wire:model.defer="selected_property_id" class="w-full px-3 py-2 border rounded"
                                            disabled>
                                            <option value="">Select Property</option>
                                            @foreach ($properties as $property)
                                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_property_id')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">House</label>
                                        <select wire:model.defer="selected_house_id" class="w-full px-3 py-2 border rounded"
                                            {{ empty($houses) ? 'disabled' : 'disabled' }}>
                                            <option value="">Select House</option>
                                            @foreach ($houses as $house)
                                                <option value="{{ $house->id }}">{{ $house->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_house_id')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700">Unit Name <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="unit_name" type="text" class="w-full px-3 py-2 border rounded">
                                        @error('unit_name')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700">Room Number <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="roomNumber" type="text" class="w-full px-3 py-2 border rounded">
                                        @error('roomNumber')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700">Rent Amount <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="rent" type="number" class="w-full px-3 py-2 border rounded">
                                        @error('rent')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700">Rent Type <span
                                                class="text-red-500">*</span></label>
                                        <select wire:model="rentTypes" class="w-full px-3 py-2 border rounded">
                                            <option value="">Select Rent Type</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="daily">Daily</option>
                                        </select>
                                        @error('rentTypes')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="type">
                                            Unit Type <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="type"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            id="type">
                                            <option value=" Apartment"> Apartment </option>
                                            <option value="commercial">Commercial</option>
                                        </select>
                                        @error('type')
                                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    @if ($houseFloor)
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-bold text-gray-700" for="floor">
                                                Floor <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model="floor"
                                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                                id="floor">
                                                <option value="">-- Select Floor --</option>
                                                @for ($i = 0; $i <= $houseFloor; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('floor')
                                                <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Notes</label>
                                    <textarea wire:model="notes" class="w-full px-3 py-2 border rounded" rows="2"></textarea>
                                </div>

                                <!-- Replace the existing Amenities section in the unit modal with this: -->
                                <div class="mb-4">
                                    <div class="flex justify-between mb-3">
                                        <label class="block text-sm font-bold text-gray-700">Amenities</label>
                                        <div class="flex space-x-2">
                                            <input wire:model="newAmenityName" type="text" class="px-3 py-1 border rounded"
                                                placeholder="New amenity name">
                                            <button type="button" wire:click="addAmenity"
                                                class="px-3 py-1 text-white bg-green-600 rounded hover:bg-green-700">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 md:grid-cols-4">
                                        @foreach ($allAmenities as $amenity)
                                            <label class="flex items-center">
                                                <input type="checkbox" wire:model="selectedAmenities"
                                                    value="{{ $amenity->name }}" class="mr-2">
                                                <span>{{ $amenity->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('newAmenityName')
                                        <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div class="flex justify-end gap-4 mt-6">
                                    <button type="button" wire:click="closeUnitModal"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Cancel
                                    </button>
                                    <button type="button" wire:click="storeUnit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        {{ $unit_id ? 'Update' : 'Create' }} Unit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingUnitDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <i class="text-red-600 fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Confirm Deletion
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this unit? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="deleteUnitConfirmed"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" wire:click="cancelDelete"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
