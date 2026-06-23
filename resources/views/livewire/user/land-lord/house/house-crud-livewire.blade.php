<div class="space-y-6">
    <!-- Top Action Card -->
    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-uds-navy">Building Inventory</h3>
            <p class="text-xs text-slate-400 font-semibold">Manage registered buildings, structural levels, and amenities.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Import Button -->
            <a href="{{ route('landlord.import.house.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-xl hover:bg-slate-200 transition duration-150">
                <i class="fas fa-file-import text-slate-500"></i>
                <span>Import Buildings</span>
            </a>
            <!-- Amenities -->
            <a href="{{ route('landlord.unit.amenities') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition duration-150">
                <i class="fas fa-sparkles text-emerald-600"></i>
                <span>Amenities Directory</span>
            </a>
            <!-- Add House -->
            <button wire:click="createHouse"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-uds-orange hover:bg-uds-orange/90 rounded-xl transition duration-150 shadow-lg shadow-orange-600/15 hover:shadow-orange-600/25">
                <i class="fas fa-plus"></i>
                <span>Add Building</span>
            </button>
        </div>
    </div>

    <!-- Table / Grid Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] overflow-hidden">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100">
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Building Name</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Associated Property (UPI)</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Floors</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Units Count</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($houses as $house)
                        <tr class="hover:bg-slate-50/40 transition duration-150">
                            <!-- Building Name -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-uds-orange flex items-center justify-center font-bold text-sm">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-uds-navy">{{ $house->name }}</span>
                                        @if($house->description)
                                            <span class="block text-[10px] text-slate-400 font-semibold truncate max-w-xs">{{ $house->description }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <!-- Associated Property -->
                            <td class="py-4 px-6">
                                <span class="block font-mono text-xs font-extrabold text-uds-navy tracking-tight">
                                    {{ $house->property->upi ?? 'N/A' }}
                                </span>
                                <span class="block text-[10px] text-slate-400 font-semibold">{{ $house->property->name ?? 'Unnamed Land' }}</span>
                            </td>
                            <!-- Floors -->
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold">
                                    {{ $house->floor }} Level(s)
                                </span>
                            </td>
                            <!-- Units Count -->
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 bg-blue-50 text-[#003b70] rounded-lg text-[10px] font-bold">
                                    {{ $house->units->count() }} Unit(s)
                                </span>
                            </td>
                            <!-- Actions -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="editHouse({{ $house->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition duration-150">
                                        <i class="fas fa-edit text-[10px]"></i>
                                        <span>Edit</span>
                                    </button>
                                    @if ($house->units->isEmpty())
                                        <button wire:click="confirmDelete({{ $house->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition duration-150">
                                            <i class="fas fa-trash text-[10px]"></i>
                                            <span>Delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-3">
                                        <i class="fas fa-home text-lg"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600">No Buildings Registered</p>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Click "Add Building" to list your first building.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse ($houses as $house)
                <div class="p-5 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-uds-orange flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-home"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-uds-navy">{{ $house->name }}</span>
                                <span class="block text-[10px] text-slate-400 font-semibold">UPI: {{ $house->property->upi ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold">
                            {{ $house->floor }} Level(s)
                        </span>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-4 py-3 px-4 bg-slate-50/50 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Land Property</span>
                            <span class="font-bold text-slate-700 truncate block">{{ $house->property->name ?? 'Unnamed Land' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Total Units</span>
                            <span class="font-bold text-slate-700">{{ $house->units->count() }} Unit(s)</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button wire:click="editHouse({{ $house->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition duration-150">
                            <i class="fas fa-edit text-[10px]"></i>
                            <span>Edit</span>
                        </button>
                        @if ($house->units->isEmpty())
                            <button wire:click="confirmDelete({{ $house->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition duration-150">
                                <i class="fas fa-trash text-[10px]"></i>
                                <span>Delete</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-3">
                            <i class="fas fa-home text-lg"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-600">No Buildings Registered</p>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Click "Add Building" to list your first building.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-4">
        {{ $houses->links('pagination::tailwind') }}
    </div>

    <!-- Add/Edit House Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
            
            <!-- Modal Card -->
            <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-md w-full border border-slate-100 transition-all transform animate-in fade-in zoom-in-95 duration-150"
                role="dialog" aria-modal="true">
                
                <form wire:submit.prevent="storeHouse">
                    <!-- Header -->
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-base font-bold text-uds-navy">
                            {{ $house_id ? 'Edit Building' : 'Add New Building' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <!-- Select Property -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="selected_property_id">
                                Property (UPI) <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selected_property_id"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="selected_property_id">
                                <option value="">Select Associated Land Property</option>
                                @foreach ($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->upi }} - {{ $property->name }}</option>
                                @endforeach
                            </select>
                            @error('selected_property_id')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Building Name -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="name">
                                Building Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="name" type="text"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="name" placeholder="e.g. Block A, Plaza, Annex">
                            @error('name')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Floors -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="floor">
                                Number of Floors (Levels) <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="floor" type="number"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="floor" placeholder="e.g. 3">
                            @error('floor')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="description">
                                Description / Notes
                            </label>
                            <textarea wire:model="description"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="description" rows="3" placeholder="Enter annotations about structure or location..."></textarea>
                            @error('description')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal" 
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                            Cancel
                        </button>
                        
                        <button type="submit"
                            class="px-5 py-2 text-xs font-bold text-white bg-uds-blue hover:bg-uds-blue/90 rounded-xl transition shadow-lg shadow-blue-900/15 hover:shadow-blue-900/25">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="cancelDelete"></div>
            
            <!-- Modal Card -->
            <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-sm w-full border border-slate-100 p-6 text-center transition-all transform animate-in fade-in zoom-in-95 duration-150"
                role="dialog" aria-modal="true">
                
                <!-- Danger Icon -->
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <!-- Title -->
                <h3 class="mb-2 text-base font-bold text-uds-navy">Delete Building</h3>
                <p class="mb-6 text-xs text-slate-400 font-semibold leading-relaxed">
                    Are you sure you want to delete this building? This action cannot be undone and will delete all related records.
                </p>
                
                <!-- Controls -->
                <div class="flex items-center justify-center gap-3">
                    <button type="button" wire:click="cancelDelete"
                        class="w-full py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition duration-150">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteHouseConfirmed"
                        class="w-full py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition duration-150 shadow-lg shadow-red-600/15">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
