<div class="p-4">
    @include('livewire.user.land-lord.adjacement.adjustment-head')

    <div class="overflow-x-auto">
        <table class="w-full table-auto border rounded-lg">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">District</th>
                    <th class="px-4 py-2 text-left">Year</th>
                    <th class="px-4 py-2 text-left">Value/m</th>
                    <th class="px-4 py-2 text-left">Tax Rate</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($districts as $index => $district)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $district['district_name'] }}</td>
                        <td class="border px-4 py-2">
                            <x-input type="number" class="w-full"
                                wire:model.lazy="districts.{{ $index }}.year" />
                        </td>
                        <td class="border px-4 py-2">
                            <x-input type="number" step="0.01" class="w-full"
                                wire:model.lazy="districts.{{ $index }}.value_per_m" />
                        </td>
                        <td class="border px-4 py-2">
                            <x-input type="number" step="0.01" class="w-full"
                                wire:model.lazy="districts.{{ $index }}.tax_rate" />
                        </td>
                        <td class="border px-4 py-2">
                            <div class="flex flex-wrap gap-2">
                                <x-button2 color="green" size="sm" action="updateDistrict({{ $index }})">
                                    Update
                                </x-button2>
                                <x-button2 color="red" size="sm" action="confirmDelete({{ $index }})">
                                    Delete
                                </x-button2>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Empty state message -->
    @if (count($districts) === 0)
        <div class="text-center py-4">
            <p class="text-gray-500">No district tax records available.</p>
        </div>
    @endif

    <!-- Modal for delete confirmation -->
    @if ($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>

            <div class="bg-white relative rounded-lg shadow-lg p-6 max-w-sm mx-4">
                <h2 class="text-lg font-bold mb-4">Confirm Deletion</h2>
                <p>Are you sure you want to delete this district tax record?</p>
                <div class="flex justify-end mt-6 space-x-4">
                    <button wire:click="$set('confirmingDelete', false)"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <x-button2 color="green" action="deleteConfirmed">
                        Yes, Delete
                    </x-button2>
                </div>
            </div>
        </div>
    @endif
</div>
