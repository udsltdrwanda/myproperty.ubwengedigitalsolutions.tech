<div class="p-4">
    @include('livewire.user.land-lord.adjacement.adjustment-head')

    <div class="mt-4 overflow-x-auto">
        <table class="w-full overflow-hidden bg-white rounded-lg shadow-md">
            <thead>
                <tr class="text-sm font-semibold text-left text-gray-600 bg-gray-100">
                    <th class="px-4 py-2">Year</th>
                    <th class="px-4 py-2">House</th>
                    <th class="px-4 py-2">Floor</th>
                    <th class="px-4 py-2">UPI</th>
                    <th class="px-4 py-2">House Value</th>
                    <th class="px-4 py-2">Tax Rate</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse ($records as $index => $record)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $record['year'] }}</td>
                        <td class="px-2 py-2">{{ $record['house_name'] }}</td>
                        <td class="px-2 py-2">{{ $record['floor'] }}</td>
                        <td class="px-2 py-2">{{ $record['upi'] ?? 'N/A' }}</td>
                        <td class="px-2 py-2">{{ $record['house_value'] }}</td>
                        <td class="px-2 py-2">{{ $record['tax_rate'] ?? '—' }}</td>
                        <td class="flex gap-2 px-2 py-2">

                            <x-button2 color="green" size="sm" action="openEditModal({{ $record['id'] }})">
                                <i class="mr-1 fas fa-edit"></i> Edit
                            </x-button2>

                            <x-button2 color="red" size="sm" wire:click="confirmDelete('{{ $record['id'] }}')">
                                Delete
                            </x-button2>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">No house records found.</td>
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
                <p class="my-3 text-sm text-gray-600">Are you sure you want to delete this house record? This action
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
                    {{ $isNewRecord ? 'Add New House Record' : 'Edit House Record' }}
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Building</label>
                        <input type="text" class="w-full px-3 py-2 bg-gray-100 border rounded-md"
                            value="{{ $house['name'] ?? '' }}" readonly />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Year</label>
                        <x-input type="number" wire:model.lazy="newRecord.year" class="w-full" />
                        @error('newRecord.year')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium">House Value</label>
                            <x-input type="number" wire:model.lazy="newRecord.house_value" class="w-full"
                                step="0.01" />
                            @error('newRecord.house_value')
                                <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                            @enderror
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium">Value per m²</label>
                            <x-input type="number" wire:model.lazy="newRecord.value_per_m" step="0.01" />
                            @error('newRecord.value_per_m') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div> --}}
                        <div>
                            <label class="block text-sm font-medium">Tax Rate (%)</label>
                            <x-input type="number" wire:model.lazy="newRecord.tax_rate" class="w-full"
                                step="0.01" />
                            @error('newRecord.tax_rate')
                                <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                            @enderror
                        </div>
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
