    <!-- Create/Edit Modal -->
    @if ($isOpen)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>

            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div
                    class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                    <div
                        class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
                        <h3 class="text-xl font-semibold">
                            {{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}
                        </h3>
                        <button wire:click="closeModal()"
                            class="float-right p-1 ml-auto text-2xl font-semibold leading-none text-black bg-transparent border-0 outline-none focus:outline-none">
                            <span class="block w-6 h-6 text-2xl">×</span>
                        </button>
                    </div>
                    <div class="relative flex-auto p-6">
                        <form>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="invoice_no">
                                        Invoice Number *
                                    </label>
                                    <input wire:model="invoice_no" id="invoice_no" type="text"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        {{ $invoice_id ? 'readonly' : '' }} readonly>
                                    @error('invoice_no')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="property_id">
                                            Property *
                                        </label>
                                        <select wire:model.live="property_id" id="property_id"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                            <option value="">Select Property</option>
                                            @foreach ($properties as $property)
                                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('property_id')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <!-- Unit selection -->

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="house_id">
                                            House *
                                        </label>
                                        <select wire:model.live="house_id" id="house_id"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            {{ !$property_id ? 'disabled' : '' }}>
                                            <option value="">Select Unit</option>
                                            @foreach ($houses as $house)
                                                <option value="{{ $house->id }}">{{ $house->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    <!-- Unit selection -->
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="unit_id">
                                            Unit *
                                        </label>
                                        <select wire:model.live="unit_id" id="unit_id"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            {{ !$house_id ? 'disabled' : '' }}>
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="tenant_id">
                                            Tenant *
                                        </label>
                                        <select wire:model="tenant_id" id="tenant_id"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                            <option value="">Select Tenant</option>

                                            @foreach ($tenants as $tenant)
                                                <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tenant_id')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <!-- Start Date -->
                                    <div>
                                        <label class="block text-sm font-medium">Start Date</label>
                                        <input type="date" wire:model.live="start_date"
                                            class="w-full p-2 border rounded" {{ !$amount ? 'disabled' : '' }} />
                                        @error('start_date')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div>
                                        <label class="block text-sm font-medium">End Date</label>
                                        <input type="date" wire:model.live="end_date"
                                            class="w-full p-2 border rounded" {{ !$start_date ? 'disabled' : '' }} />
                                        @error('end_date')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-700" for="amount">
                                            Amount * {{ $rentTypes }}
                                        </label>
                                        <input wire:model="amount" id="amount" type="number" step="0.01"
                                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                            @if ($unit_rent) readonly @endif>
                                        @if ($unit_rent)
                                            <p class="mt-1 text-xs text-gray-500">
                                                Auto-filled with unit rent amount. To change, first select "Select Unit"
                                                option.
                                            </p>
                                        @endif
                                        @error('amount')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">VAT (18%)</label>
                                        <input type="number" wire:model="vat" readonly
                                            class="w-full p-2 text-gray-600 bg-gray-100 border rounded">
                                        @error('vat')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">
                                        Total Amount <br>
                                        ( (Amount: {{ $amount }} + VAT:{{ $vat }} ) *
                                        {{ $durationUnits }} )
                                    </label>
                                    <input type="text" wire:model="total_amount" readonly
                                        class="w-full px-3 py-2 text-gray-700 bg-gray-100 border rounded shadow focus:outline-none focus:shadow-outline" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center justify-end p-6 border-t border-solid rounded-b border-blueGray-200">
                        <button wire:click="closeModal()" type="button"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-red-500 uppercase transition-all duration-150 ease-linear outline-none background-transparent focus:outline-none">
                            Cancel
                        </button>
                        <button wire:click="store()" type="button"
                            class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-white uppercase transition-all duration-150 ease-linear bg-green-500 rounded shadow outline-none active:bg-green-600 hover:shadow-lg focus:outline-none">
                            Save Changes
                        </button>
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
            <div class="relative w-auto max-w-sm mx-auto my-6">
                <div
                    class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
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
                            Are you sure you want to delete this invoice? This action cannot be undone.
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
