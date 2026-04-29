    <!-- Create/Edit Modal -->
    @if ($isOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div
                    class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                    <div
                        class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
                        <h3 class="text-xl font-semibold">
                            {{ $tenant_id ? 'Edit Tenant' : 'Create New Tenant' }}
                        </h3>
                        <button wire:click="closeModal()"
                            class="float-right p-1 ml-auto text-2xl font-semibold leading-none text-black bg-transparent border-0 outline-none focus:outline-none">
                            <span class="block w-6 h-6 text-2xl">×</span>
                        </button>
                    </div>
                    <div class="relative flex-auto p-6">
                        <form>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="tenant_id">
                                        Tenant ID (16 characters) *
                                    </label>
                                    <input wire:model="tenant_id" id="tenant_id" type="number"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        maxlength="16"
                                        oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);">

                                    @error('tenant_id')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="tenant_name">
                                        Tenant Name *
                                    </label>
                                    <input wire:model="tenant_name" id="tenant_name" type="text"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                    @error('tenant_name')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="phone">
                                        Phone *
                                    </label>
                                    <input wire:model="phone" id="phone" type="text"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                    @error('phone')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                                        Email *
                                    </label>
                                    <input wire:model="email" id="email" type="email"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                    @error('email')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="company_name">
                                        Company Name
                                    </label>
                                    <input wire:model="company_name" id="company_name" type="text"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="company_tin">
                                        Company TIN *
                                    </label>
                                    <input id="company_tin" name="company_tin" type="number"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        maxlength="9"
                                        oninput="if(this.value.length >9) this.value = this.value.slice(0, 9);">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="notes">
                                    Notes
                                </label>
                                <textarea wire:model="notes" id="notes" rows="3"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200">
                        <button wire:click="closeModal()" type="button"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-red-500 uppercase transition-all duration-150 ease-linear outline-none background-transparent focus:outline-none">
                            Cancel
                        </button>
                        <x-button2 type="submit" color="blue" action="store"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-white uppercase transition-all duration-150 ease-linear bg-green-500 rounded shadow outline-none active:bg-green-600 hover:shadow-lg focus:outline-none">
                            Save Changes
                        </x-button2>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed inset-0 z-40 bg-black opacity-25"></div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($deleteModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="relative w-auto max-w-sm mx-auto my-6">
                <div
                    class="flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                    <div
                        class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
                        <h3 class="text-xl font-semibold">
                            Confirm Deletion
                        </h3>
                        <button wire:click="$set('deleteModal', false)"
                            class="float-right p-1 ml-auto text-2xl font-semibold leading-none text-black bg-transparent border-0 outline-none focus:outline-none">
                            <span class="block w-6 h-6 text-2xl">×</span>
                        </button>
                    </div>
                    <div class="relative flex-auto p-6">
                        <p class="my-4 text-lg leading-relaxed text-blueGray-500">
                            Are you sure you want to delete this tenant? This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200">
                        <button wire:click="$set('deleteModal', false)" type="button"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-gray-500 uppercase transition-all duration-150 ease-linear outline-none background-transparent focus:outline-none">
                            Cancel
                        </button>
                        <button wire:click="delete()" type="button"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-white uppercase transition-all duration-150 ease-linear bg-red-500 rounded shadow outline-none active:bg-red-600 hover:shadow-lg focus:outline-none">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed inset-0 z-40 bg-black opacity-25"></div>
    @endif
