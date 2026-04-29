<div class="p-4 bg-white rounded shadow">
    <h2 class="mb-4 text-lg font-semibold">Unit Amenities Management</h2>

    <div class="flex flex-row items-center mb-4 space-y-2 md:flex-row md:space-x-4 md:space-y-0">
        <div class="w-full md:w-1/3">
            <label class="block mb-1 text-sm font-medium text-gray-700">Filter by Property</label>
            <select wire:model.live="selected_property_id" class="w-full px-3 py-2 border rounded">
                <option value="">All Properties</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-1/6">
            <label class="block mb-1 text-sm font-medium text-gray-700">Per Page</label>
            <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div class="flex items-end justify-end w-full md:w-1/6">
            <x-button2 action="createAmenity" color="blue">
                + Add Amenity
            </x-button2>
        </div>
    </div>

    @if ($amenities->count())
        <div class="overflow-x-auto">
            <table class="w-full border table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">
                            <div class="flex items-center cursor-pointer" wire:click="sortBy('name')">
                                Amenity Name
                                @if ($sortField === 'name')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th class="p-2 text-left">
                            <div class="flex items-center cursor-pointer" wire:click="sortBy('property_id')">
                                Property
                                @if ($sortField === 'property_id')
                                    <span class="ml-1">
                                        @if ($sortDirection === 'asc')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </th>
                        <th class="p-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amenities as $amenity)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2">{{ $amenity->name }}</td>
                            <td class="p-2">{{ $amenity->property->name }}</td>
                            <td class="flex justify-center p-2 space-x-2">
                                <x-button2 action="editAmenity({{ $amenity->id }})" color="blue" size="sm" >
                                    <i class="mr-1 fas fa-edit"></i> Edit
                                </x-button2>
                                <x-button2 color="red" action="confirmDeleteAmenity({{ $amenity->id }})" size="sm" >
                                    <i class="mr-1 fas fa-trash"></i> Delete
                                </x-button2>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $amenities->links() }}
        </div>
    @else
        <div class="p-4 text-center bg-gray-100 rounded">
            <p class="text-gray-600">No amenities found.
                @if ($search)
                    Try a different search term.
                @else
                    Please add some amenities.
                @endif
            </p>
        </div>
    @endif

    {{-- Amenity Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative z-20 w-full max-w-md p-6 my-8 bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $amenity_id ? 'Edit Amenity' : 'Create Amenity' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <form>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Property</label>
                        <select wire:model="property_id" class="w-full px-3 py-2 border rounded">
                            <option value="">Select Property</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Amenity Name</label>
                        <input wire:model="name" type="text" class="w-full px-3 py-2 border rounded" placeholder="e.g., Swimming Pool, Gym, Parking">
                        @error('name')
                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-button2 color="gray" action="closeModal">
                            Cancel
                        </x-button2>
                        <x-button2 action="storeAmenity" color="green" >
                            {{ $amenity_id ? 'Update' : 'Create' }} Amenity
                        </x-button2>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Confirmation Modal --}}
    @if ($confirmingDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative z-40 w-full max-w-md p-6 bg-white rounded shadow-xl">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Confirm Deletion</h3>
                <p class="mb-6 text-gray-600">Are you sure you want to delete this amenity? This action cannot be
                    undone.</p>
                <div class="flex justify-end space-x-3">
                    <x-button2 action="cancelDelete" color="gray">
                        Cancel
                    </x-button2>
                    <x-button2 action="deleteAmenityConfirmed" color="red">
                        Yes, Delete
                    </x-button2>
                </div>
            </div>
        </div>
    @endif
</div>
