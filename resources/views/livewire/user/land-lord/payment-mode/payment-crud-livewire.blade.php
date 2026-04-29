<div class="p-4 mx-auto space-y-4">

    <x-button2 color="green" action="openCreateModal" icon="fas fa-plus">
        Add Payment Mode
    </x-button2>
    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                    <h2 class="mb-4 text-lg font-bold">
                        {{ $editingId ? 'Edit Payment Mode' : 'Add Payment Mode' }}
                    </h2>
                    <form wire:submit.prevent="save" class="space-y-2">
                        <div>
                            <label class="block text-sm">Account Name</label>
                            <input type="text" wire:model.defer="account_name"
                                class="w-full px-2 py-1 border rounded">
                            @error('account_name')
                                <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm">Account Number</label>
                            <input type="text" wire:model.defer="account_number"
                                class="w-full px-2 py-1 border rounded">
                            @error('account_number')
                                <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-4 space-x-2">
                            <x-button2 color="gray" wire:click="$set('showModal', false)">
                                Cancel
                            </x-button2>
                            <x-button2 type="submit" color="blue" action="save">
                                Save
                            </x-button2>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Payment List --}}
    <h3 class="text-lg font-semibold">Your Payment Modes</h3>
    <ul class="space-y-2">
        @forelse($paymentModes as $mode)
            <li class="flex items-center justify-between p-3 border rounded">
                <div>
                    <strong>{{ $mode->account_name }}</strong> — {{ $mode->account_number }}
                </div>
                <div class="space-x-2">
                    @if ($mode->paymentRecords->isEmpty())
                        <x-button2 color="blue" action="openEditModal({{ $mode->id }})" icon="fas fa-edit">
                            Edit
                        </x-button2>

                        <x-button2 color="red" action="confirmDelete({{ $mode->id }})" icon="fas fa-trash">
                            Delete
                        </x-button2>
                    @else
                        <span class="text-sm italic text-gray-500">In use</span>
                    @endif
                </div>
            </li>
        @empty
            <li>No payment modes yet.</li>
        @endforelse
    </ul>


    {{-- Delete Confirmation --}}
    @if ($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>

            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div class="w-full max-w-sm p-6 bg-white rounded shadow-lg">
                    <h2 class="mb-4 text-lg font-bold">Confirm Deletion</h2>
                    <p>Are you sure you want to delete this payment mode?</p>
                    <div class="flex justify-end mt-4 space-x-2">
                        <x-button color="gray" wire:click="$set('confirmingDeleteId', null)">
                            Cancel
                        </x-button>
                        <button type="button" wire:click="delete" class="px-4 py-2 text-white bg-red-500 rounded">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
