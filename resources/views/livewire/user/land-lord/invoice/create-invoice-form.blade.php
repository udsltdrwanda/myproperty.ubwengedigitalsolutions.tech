    <!-- Create / Edit Invoice Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto"
             style="padding-top: 2rem; padding-bottom: 2rem;">
            <!-- Blurry Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 transition-opacity"
                 style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
                 wire:click="closeModal()"></div>

            <!-- Modal Card -->
            <div class="relative w-full max-w-2xl bg-white rounded-2xl border border-slate-100 shadow-2xl overflow-hidden my-auto"
                 role="dialog" aria-modal="true">

                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/60 sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                             style="background: linear-gradient(135deg,#003b70 0%,#0b2545 100%);">
                            <i class="fas fa-file-invoice-dollar text-white text-xs"></i>
                        </div>
                        <h3 class="text-sm font-extrabold text-uds-navy uppercase tracking-wide">
                            {{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" type="button"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Scrollable Body -->
                <div class="overflow-y-auto" style="max-height: 70vh;">
                    <div class="p-6 space-y-5">

                        <!-- Invoice Number -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="invoice_no">
                                Invoice Number
                            </label>
                            <input wire:model="invoice_no" id="invoice_no" type="text" readonly
                                class="w-full px-3 py-2 text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">
                            @error('invoice_no')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Grid: Property + House -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Property -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="property_id">
                                    Property *
                                </label>
                                <select id="property_id" disabled
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">
                                    <option value="">Select Property</option>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}" @if($property_id == $property->id) selected @endif>
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="property_id" value="{{ $property_id }}">
                                @error('property_id')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- House -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="house_id">
                                    House *
                                </label>
                                <select wire:model.live="house_id" id="house_id"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition
                                           {{ !$property_id ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}"
                                    {{ !$property_id ? 'disabled' : '' }}>
                                    <option value="">Select House</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                                    @endforeach
                                </select>
                                @error('house_id')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="unit_id">
                                    Unit *
                                </label>
                                <select wire:model.live="unit_id" id="unit_id"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition
                                           {{ !$house_id ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}"
                                    {{ !$house_id ? 'disabled' : '' }}>
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tenant -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="tenant_id">
                                    Tenant *
                                </label>
                                <select wire:model="tenant_id" id="tenant_id"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition">
                                    <option value="">Select Tenant</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }}</option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                                    Start Date
                                </label>
                                <input type="date" wire:model.live="start_date"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition
                                           {{ !$amount ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}"
                                    {{ !$amount ? 'disabled' : '' }}>
                                @error('start_date')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                                    End Date
                                </label>
                                <input type="date" wire:model.live="end_date"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition
                                           {{ !$start_date ? 'bg-slate-50 text-slate-400 cursor-not-allowed' : '' }}"
                                    {{ !$start_date ? 'disabled' : '' }}>
                                @error('end_date')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="amount">
                                    Amount * ({{ $rentTypes }})
                                </label>
                                <input wire:model="amount" id="amount" type="number" step="0.01"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition
                                           {{ $unit_rent ? 'bg-slate-50 text-slate-500 cursor-not-allowed' : '' }}"
                                    @if ($unit_rent) readonly @endif>
                                @if ($unit_rent)
                                    <span class="text-[10px] text-uds-blue font-bold mt-1 block">Auto-filled from unit rent. Clear Unit to change.</span>
                                @endif
                                @error('amount')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- VAT -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                                    VAT (18%)
                                </label>
                                <input type="number" wire:model="vat" readonly
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed">
                                @error('vat')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Amount Summary -->
                        <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-uds-blue uppercase tracking-wider block">Total Invoice Amount</span>
                                <span class="text-[9px] text-slate-400 mt-0.5 block">
                                    (Amount: {{ $amount ?: 0 }} + VAT: {{ $vat ?: 0 }}) × {{ $durationUnits ?: 0 }} month(s)
                                </span>
                            </div>
                            <div>
                                <input type="text" wire:model="total_amount" readonly
                                    class="w-40 px-3 py-2 text-right text-sm font-extrabold text-uds-navy bg-white border border-blue-100 rounded-xl cursor-not-allowed text-right">
                            </div>
                        </div>

                    </div><!-- end scrollable body -->
                </div>

                <!-- Sticky Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/60 sticky bottom-0 z-10">
                    <button wire:click="closeModal()" type="button"
                        class="px-5 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="store()" type="button"
                        class="px-6 py-2.5 text-xs font-bold text-white bg-uds-blue hover:bg-uds-blue/90 rounded-xl shadow-lg transition">
                        {{ $invoice_id ? 'Update Invoice' : 'Save Invoice' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40"
                 style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);"
                 wire:click="$set('deleteModal', false)"></div>

            <div class="relative w-full max-w-sm bg-white rounded-2xl border border-slate-100 shadow-2xl p-6 text-center">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <h3 class="text-sm font-extrabold text-uds-navy uppercase tracking-wide mb-2">Confirm Deletion</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Are you sure you want to delete this invoice? This action cannot be undone.
                </p>
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('deleteModal', false)" type="button"
                        class="px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="delete()" type="button"
                        class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-md">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
