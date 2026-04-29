<div class="p-4">

    {{-- Button to open create form --}}
    <div class="flex justify-end w-full mb-4">
        <x-button wire:click="openCreateModal">+ New Manager</x-button>
    </div>

    {{-- Manager List --}}
    <div class="mt-4">
        <h2 class="mb-4 text-lg font-bold">Registered Managers</h2>
        <table class="w-full text-sm border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left border">#</th>
                    <th class="p-2 text-left border">Name</th>
                    <th class="p-2 text-left border">Email</th>
                    <th class="p-2 text-left border">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($managers as $index => $manager)
                    <tr>
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $manager->name }}</td>
                        <td class="p-2 border">{{ $manager->email }}</td>
                        <td class="p-2 border">
                            <button wire:click="openEditModal({{ $manager->id }})" class="text-blue-600 hover:underline">Edit</button>
                            <button wire:click="openDeleteModal({{ $manager->id }})" class="ml-2 text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No managers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Modal --}}
    @if ($isFormModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-lg font-semibold">{{ $isEditing ? 'Edit Manager' : 'Register Manager' }}</h2>
            <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                <div class="mb-2">
                    <x-label for="name" value="Name" />
                    <x-input wire:model.defer="name" id="name" class="w-full" />
                    @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="mb-2">
                    <x-label for="email" value="Email" />
                    <x-input wire:model.defer="email" id="email" type="email" class="w-full" />
                    @error('email') <span class="text-sm text-red-600">{{ $message??'' }}</span> @enderror
                </div>

                @if (!$isEditing)
                    <div class="mb-2">
                        <x-label for="password" value="Password" />
                        <x-input wire:model.defer="password" id="password" type="password" class="w-full" />
                        @error('password') <span class="text-sm text-red-600">{{ $message??'' }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <x-label for="password_confirmation" value="Confirm Password" />
                        <x-input wire:model.defer="password_confirmation" id="password_confirmation" type="password" class="w-full" />
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="flex items-center mt-2">
                            <input type="checkbox" wire:model.defer="terms" id="terms" class="rounded">
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                I agree to the terms and privacy policy
                            </label>
                        </div>
                        @error('terms') <span class="text-sm text-red-600">{{ $message??'' }}</span> @enderror
                    @endif
                @endif

                <div class="flex justify-end mt-4">
                    <x-button type="submit">{{ $isEditing ? 'Update' : 'Register' }}</x-button>
                    <button type="button" wire:click="$set('isFormModalOpen', false)" class="px-4 py-2 ml-2 bg-gray-300 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($isDeleteModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-700 bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-red-600">Confirm Deletion</h2>
            <p>Are you sure you want to remove this manager?</p>

            <div class="flex justify-end mt-4">
                <x-button wire:click="delete" class="bg-red-600 hover:bg-red-700">Yes, Delete</x-button>
                <button wire:click="$set('isDeleteModalOpen', false)" class="px-4 py-2 ml-2 bg-gray-300 rounded">Cancel</button>
            </div>
        </div>
    </div>
    @endif

</div>
