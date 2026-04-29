<div class="p-4">
    @include('livewire.user.land-lord.adjacement.adjustment-head')

    {{-- <div class="flex items-center justify-between mb-4">
        <x-input type="search" placeholder="Search by UPI or name..." wire:model.debounce.500ms="search" class="w-1/3" />
        <x-button2 color="blue" wire:click="openEditModal">
            <i class="mr-1 fas fa-plus"></i> Add New Record
        </x-button2>
    </div> --}}

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-4 py-2">Year</th>
                    <th class="px-4 py-2">UPI</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">District</th>
                    <th class="px-4 py-2">Sector</th>
                    <th class="px-4 py-2">Cell</th>
                    <th class="px-4 py-2">Tax Rate/m²</th>
                    <th class="px-4 py-2">Land Value</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $record['year'] }}</td>
                        <td class="px-4 py-2">{{ $record['upi'] }}</td>
                        <td class="px-4 py-2">{{ $record['name'] }}</td>
                        <td class="px-4 py-2">{{ $record['district'] }}</td>
                        <td class="px-4 py-2">{{ $record['sector'] }}</td>
                        <td class="px-4 py-2">{{ $record['cell'] }}</td>
                        <td class="px-4 py-2">{{ $record['value_per_m'] }}</td>
                        <td class="px-4 py-2">{{ $record['land_value'] }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                <x-button2 color="green" size="sm"
                                    wire:click="openEditModal({{ $record['id'] }})">
                                    <i class="mr-1 fas fa-edit"></i> Edit
                                </x-button2>
                                <x-button2 color="red" size="sm"
                                    wire:click="confirmDelete({{ $record['id'] }})">
                                    <i class="mr-1 fas fa-trash"></i> Delete
                                </x-button2>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-4 text-center text-gray-500">
                            No land adjacement records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative w-full max-w-md p-6 bg-white rounded-lg shadow-lg ">
                <h2 class="text-lg font-semibold">Confirm Deletion</h2>
                <p class="my-3 text-sm text-gray-600">Are you sure you want to delete this land record? This action
                    cannot be undone.</p>
                <div class="flex justify-end mt-4 space-x-3">
                    <x-button2 color="gray" wire:click="cancelDelete">Cancel</x-button2>
                    <x-button2 color="red" wire:click="deleteRecord">Delete</x-button2>
                </div>
            </div>
        </div>
    @endif

    {{-- Create/Edit Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative w-full max-w-xl p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-lg font-semibold">
                    {{ $isNewRecord ? 'Add New Land Record' : 'Edit Land Record' }}
                </h2>

                <div class="space-y-4">
                    @if ($isNewRecord)
                        <div>
                            <label class="block text-sm font-medium">Property</label>
                            <select wire:model="newRecord.property_id" class="w-full px-3 py-2 border rounded-md">
                                <option value="">Select property</option>
                                @foreach ($properties as $prop)
                                    <option value="{{ $prop['id'] }}">{{ $prop['upi'] }} - {{ $prop['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('newRecord.property_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium">Property</label>
                            <input type="text" class="w-full px-3 py-2 bg-gray-100 border rounded-md"
                                value="{{ $property['upi'] ?? '' }} - {{ $property['name'] ?? '' }}" readonly />
                        </div>
                    @endif


                    <div>
                        <label class="block text-sm font-medium">Year</label>
                        <x-input type="number" wire:model.lazy="newRecord.year" class="w-full "/>
                        @error('newRecord.year')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Land Value</label>
                            <x-input type="number" wire:model.lazy="newRecord.land_value" step="0.01" />
                            @error('newRecord.land_value') <span class="text-xs text-red-500">{{ $message??''}}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Value per m²</label>
                            <x-input type="number" wire:model.lazy="newRecord.value_per_m" step="0.01" class="w-full" />
                            @error('newRecord.value_per_m')
                                <span class="text-xs text-red-500">{{ $message??'' }}</span>
                            @enderror
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium">Tax Rate (%)</label>
                            <x-input type="number" wire:model.lazy="newRecord.tax_rate" step="0.01" class="w-full"  />
                            @error('newRecord.tax_rate')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div> --}}
                    </div>
                </div>

                <div class="flex justify-end mt-5 space-x-3">
                    <x-button2 color="gray" wire:click="closeEditModal">Cancel</x-button2>
                    <x-button2 color="blue" wire:click="saveRecord">
                        {{ $isNewRecord ? 'Create' : 'Update' }}
                    </x-button2>
                </div>
            </div>
        </div>
    @endif
</div>
