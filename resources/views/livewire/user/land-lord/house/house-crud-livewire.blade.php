<div class="p-8 bg-white shadow-lg rounded-xl">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-6">
            <h2 class="text-2xl font-bold text-gray-800">Building Management</h2>
            <a href="{{ route('landlord.import.house.index') }}"
                class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import
            </a>
        </div>
        <div class="flex items-center gap-4">
            <x-button2 action="createHouse" size="sm" color="green" class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add House
            </x-button2>

            <a href="{{ route('landlord.unit.amenities') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Amenities
            </a>
        </div>
    </div>

    <!-- House List Table -->
    <div class="overflow-hidden border border-gray-200 rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs font-medium text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4">Property UPI</th>
                    <th scope="col" class="px-6 py-4">House Name</th>
                    <th scope="col" class="px-6 py-4">Floor</th>
                    <th scope="col" class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($houses as $house)
                    <tr class="transition-colors duration-200 bg-white hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $house->property->upi ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $house->name }}</td>
                        <td class="px-6 py-4">{{ $house->floor }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <button wire:click="editHouse({{ $house->id }})"
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 transition-colors duration-200 rounded-md hover:bg-blue-50">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                @if ($house->units->isEmpty())
                                    <button wire:click="confirmDelete({{ $house->id }})"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 transition-colors duration-200 rounded-md hover:bg-red-50">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-lg font-medium">No houses found</p>
                                <p class="text-sm text-gray-500">Add your first house to get started</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-6">
        {{ $houses->links('pagination::tailwind') }}
    </div>

    {{-- Livewire Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black/50">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="relative w-full max-w-md p-8 mx-auto bg-white shadow-2xl rounded-xl">
                <button type="button" wire:click="closeModal"
                    class="absolute text-gray-400 top-4 right-4 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h3 class="mb-6 text-2xl font-semibold text-gray-900">
                    {{ $house_id ? 'Edit House' : 'Add House' }}
                </h3>

                <form class="space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Property (UPI)</label>
                        <select wire:model="selected_property_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Property(UPI)</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->upi }} - {{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_property_id')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">House Name</label>
                        <input type="text" id="name" wire:model="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="floor" class="block mb-2 text-sm font-medium text-gray-700">Floor Number</label>
                        <input type="number" id="floor" wire:model="floor"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('floor')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" wire:model="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('description')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <x-button2 action="closeModal" color="gray" size="sm" class="px-6">
                            Cancel
                        </x-button2>
                        <x-button2 action="storeHouse" size="sm" color="green" class="px-6">
                            {{ $house_id ? 'Update' : 'Create' }}
                        </x-button2>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="relative w-full max-w-sm p-8 bg-white shadow-2xl rounded-xl">
                <div class="flex items-center justify-center mb-6">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-center text-gray-900">Confirm Deletion</h3>
                <p class="mb-6 text-sm text-center text-gray-600">Are you sure you want to delete this house? This action cannot be undone.</p>
                <div class="flex justify-center space-x-4">
                    <button wire:click="cancelDelete"
                        class="px-6 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button wire:click="deleteHouseConfirmed"
                        class="px-6 py-2 text-sm font-medium text-white transition-colors duration-200 bg-red-600 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
