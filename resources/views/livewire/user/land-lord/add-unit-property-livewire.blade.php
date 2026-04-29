@if ($showUnitModal)
    <div class="fixed inset-0 z-10 flex items-center justify-center bg-black bg-opacity-50">
        <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
        <div class="relative z-20 w-full max-w-2xl p-6 bg-white rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium">
                    {{ $unit_id ? 'Edit Unit' : 'Create Unit' }}
                    @if ($currentPropertyId)
                        (Property: {{ App\Models\Property::find($currentPropertyId)->name }})
                    @endif
                </h3>
                <button wire:click="closeUnitModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <form>
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
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
                        <label class="block mb-2 text-sm font-bold text-gray-700">Rent Amount</label>
                        <input wire:model="rent" type="number" class="w-full px-3 py-2 border rounded">
                        @error('rent')
                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Rent Type</label>
                        <select wire:model="rentTypes" class="w-full px-3 py-2 border rounded">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                        @error('rentTypes')
                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Status</label>
                        <select wire:model="unit_status" class="w-full px-3 py-2 border rounded">
                            <option value="occupied">Occupied</option>
                            <option value="vacant">Vacant</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        @error('unit_status')
                            <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-bold text-gray-700">Notes</label>
                    <textarea wire:model="notes" class="w-full px-3 py-2 border rounded" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-bold text-gray-700">Amenities</label>
                    <div class="grid grid-cols-2 gap-2 md:grid-cols-4">
                        @foreach ($allAmenities as $amenity)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedAmenities" value="{{ $amenity->name }}"
                                    class="mr-2">
                                <span>{{ $amenity->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div>

                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="closeUnitModal"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <x-button2 type="submit" action="storeUnit" size="sm" color="green">
                        {{ $unit_id ? 'Update' : 'Create' }} Unit
                    </x-button2>
                </div>
            </form>
        </div>
    </div>
@endif
