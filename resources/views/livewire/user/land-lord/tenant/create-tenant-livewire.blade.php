    {{-- ════ Create / Edit Modal ════ --}}
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(11,37,69,0.55); backdrop-filter:blur(6px);">
            <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-2xl overflow-hidden">

                {{-- Sticky Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(243,146,0,0.2);">
                            <i class="fas fa-{{ $record_id ? 'edit' : 'user-plus' }} text-sm" style="color:#f39200;"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-white">{{ $record_id ? 'Edit Client' : 'Add New Client' }}</h3>
                            <p class="text-[10px] font-semibold" style="color:rgba(255,255,255,0.5);">Fill in the client details below</p>
                        </div>
                    </div>
                    <button wire:click="closeModal()"
                        class="w-8 h-8 rounded-xl flex items-center justify-center transition"
                        style="color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.1);"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                {{-- Scrollable Body --}}
                <div class="overflow-y-auto flex-1 p-6">
                    @php
                        $inputCls = "w-full px-3 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl transition duration-150 focus:outline-none";
                        $labelCls = "block text-[10px] font-bold uppercase tracking-wider mb-1.5 text-slate-400";
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tenant ID --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-id-card mr-1"></i> Tenant ID (16 digits) *</label>
                            <input wire:model="tenant_id" type="number" maxlength="16"
                                   oninput="if(this.value.length>16) this.value=this.value.slice(0,16);"
                                   class="{{ $inputCls }}" placeholder="1234567890123456">
                            @error('tenant_id') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tenant Name --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-user mr-1"></i> Full Name *</label>
                            <input wire:model="tenant_name" type="text" class="{{ $inputCls }}" placeholder="e.g. Jean Claude">
                            @error('tenant_name') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-phone mr-1"></i> Phone *</label>
                            <input wire:model="phone" type="text" class="{{ $inputCls }}" placeholder="e.g. 0788000000">
                            @error('phone') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-envelope mr-1"></i> Email *</label>
                            <input wire:model="email" type="email" class="{{ $inputCls }}" placeholder="e.g. name@example.com">
                            @error('email') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Company Name --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-building mr-1"></i> Company Name</label>
                            <input wire:model="company_name" type="text" class="{{ $inputCls }}" placeholder="Optional">
                        </div>

                        {{-- Company TIN --}}
                        <div>
                            <label class="{{ $labelCls }}"><i class="fas fa-receipt mr-1"></i> Company TIN</label>
                            <input wire:model="company_tin" type="number" maxlength="9"
                                   oninput="if(this.value.length>9) this.value=this.value.slice(0,9);"
                                   class="{{ $inputCls }}" placeholder="9-digit TIN">
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mt-4">
                        <label class="{{ $labelCls }}"><i class="fas fa-sticky-note mr-1"></i> Notes</label>
                        <textarea wire:model="notes" rows="3" class="{{ $inputCls }}" placeholder="Any additional notes…"></textarea>
                    </div>
                </div>

                {{-- Sticky Footer --}}
                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/50 shrink-0">
                    <button wire:click="closeModal()" type="button"
                        class="px-5 py-2 text-xs font-bold border border-slate-200 rounded-xl text-slate-500 bg-white hover:bg-slate-100 transition duration-150">
                        Cancel
                    </button>
                    <button wire:click="store()" type="button"
                        class="px-5 py-2 text-xs font-bold text-white rounded-xl transition duration-150 shadow-md"
                        style="background:linear-gradient(135deg,#003b70,#0b2545);">
                        <i class="fas fa-save mr-1.5"></i>
                        {{ $record_id ? 'Update Client' : 'Save Client' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ════ Delete Confirmation Modal ════ --}}
    @if ($deleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(11,37,69,0.55); backdrop-filter:blur(6px);">
            <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-50">
                        <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Confirm Deletion</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">This action cannot be undone</p>
                    </div>
                </div>
                {{-- Body --}}
                <div class="px-6 py-5">
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Are you sure you want to permanently delete this client? All associated data will be lost.
                    </p>
                </div>
                {{-- Footer --}}
                <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    <button wire:click="$set('deleteModal', false)" type="button"
                        class="px-4 py-2 text-xs font-bold border border-slate-200 rounded-xl text-slate-500 bg-white hover:bg-slate-100 transition">
                        Cancel
                    </button>
                    <button wire:click="delete()" type="button"
                        class="px-4 py-2 text-xs font-bold text-white bg-red-500 rounded-xl hover:bg-red-600 transition shadow-md">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
