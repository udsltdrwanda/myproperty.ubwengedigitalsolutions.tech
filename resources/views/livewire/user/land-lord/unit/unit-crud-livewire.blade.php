<div class="space-y-6">
    <!-- Top Action & Filter Card -->
    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-6">
        <!-- Title & Action Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-uds-navy">Unit Management Directory</h3>
                <p class="text-xs text-slate-400 font-semibold">Filter and manage individual rental rooms, apartments, and suites.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="loadAllUnits" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-xl hover:bg-slate-200 transition duration-150">
                    <i class="fas fa-list text-slate-500"></i>
                    <span>View All Units</span>
                </button>
                
                <!-- Add Unit (Only if building is selected) -->
                <button wire:click="createUnit({{ $selected_property_id ? $selected_property_id : 'null' }}, {{ $selected_house_id ? $selected_house_id : 'null' }})"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-uds-orange hover:bg-uds-orange/90 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl transition duration-150 shadow-lg shadow-orange-600/15 hover:shadow-orange-600/25"
                    {{ empty($selected_house_id) ? 'disabled' : '' }}>
                    <i class="fas fa-plus"></i>
                    <span>Add Unit</span>
                </button>

                <!-- Import Excel (Only if building is selected) -->
                <a href="{{ route('landlord.import.unit.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl transition duration-150"
                    @if(empty($selected_house_id)) style="pointer-events: none; opacity: 0.5;" @endif>
                    <i class="fas fa-file-import text-emerald-600"></i>
                    <span>Import Units</span>
                </a>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
            <!-- Land Property Filter -->
            <div>
                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="selected_property_id">
                    Filter Land Property
                </label>
                <select wire:model.live="selected_property_id"
                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                    id="selected_property_id">
                    <option value="">All Properties (Land)</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->upi }} &nbsp;-&nbsp; {{ $property->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- House / Building Filter -->
            <div>
                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="selected_house_id">
                    Filter Building / Structure
                </label>
                <select wire:model.live="selected_house_id"
                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150 disabled:bg-slate-50 disabled:cursor-not-allowed"
                    id="selected_house_id"
                    {{ empty($houses) && $selected_property_id ? 'disabled' : '' }}>
                    <option value="">All Buildings</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table / Grid Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] overflow-hidden">
        @if (count($units))
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100">
                            @if ($viewAllUnits || !$selected_house_id)
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Property & Building</th>
                            @endif
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unit / Room Details</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Rent (Rwf)</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Billing Mode</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Lease Info</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($units as $unit)
                            <tr class="hover:bg-slate-50/40 transition duration-150">
                                @if ($viewAllUnits || !$selected_house_id)
                                    <!-- Property & Building -->
                                    <td class="py-4 px-6">
                                        <span class="block text-xs font-bold text-uds-navy">{{ $unit->house->name ?? 'N/A' }}</span>
                                        <span class="block text-[10px] text-slate-400 font-semibold">{{ $unit->house->property->name ?? 'N/A' }}</span>
                                    </td>
                                @endif
                                
                                <!-- Unit Name & Room -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-uds-blue flex items-center justify-center font-bold text-sm">
                                            <i class="fas fa-door-open"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-bold text-uds-navy">{{ $unit->name }}</span>
                                            <span class="block text-[10px] text-slate-400 font-semibold">Room: {{ $unit->roomNumber }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rent Amount -->
                                <td class="py-4 px-6 text-right">
                                    <span class="font-mono text-xs font-bold text-uds-navy">
                                        {{ number_format($unit->rent) }} <span class="text-[10px] text-slate-400 font-bold">RWF</span>
                                    </span>
                                </td>

                                <!-- Billing Mode -->
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase">
                                        {{ $unit->rentTypes }}
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6 text-center">
                                    @if ($unit->activeRentRecord)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-100">
                                            Occupied
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                            {{ $unit->unit_status ?? 'Available' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Lease Info -->
                                <td class="py-4 px-6 text-center">
                                    @if ($unit->activeRentRecord)
                                        @php
                                            $endDate = \Carbon\Carbon::parse($unit->activeRentRecord->end_date);
                                            $now = \Carbon\Carbon::now();
                                            $diff = $now->diff($endDate);
                                        @endphp

                                        @if ($endDate->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-100">
                                                Expired
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-100">
                                                {{ $diff->m }}m {{ $diff->d }}d left
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-[10px] text-slate-400 font-semibold">No Active Lease</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($unit->activeRentRecord)
                                            <span class="text-[10px] text-slate-400 font-bold">Leased</span>
                                        @else
                                            <button wire:click="editUnit({{ $unit->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition duration-150">
                                                <i class="fas fa-edit text-[10px]"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button wire:click="confirmDeleteUnit({{ $unit->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition duration-150">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                <span>Delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="block lg:hidden divide-y divide-slate-100">
                @foreach ($units as $unit)
                    <div class="p-5 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-uds-blue flex items-center justify-center font-bold text-sm">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-uds-navy">{{ $unit->name }}</span>
                                    <span class="block text-[10px] text-slate-400 font-semibold">Room: {{ $unit->roomNumber }}</span>
                                </div>
                            </div>
                            @if ($unit->activeRentRecord)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-100">
                                    Occupied
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    {{ $unit->unit_status ?? 'Available' }}
                                </span>
                            @endif
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4 py-3 px-4 bg-slate-50/50 rounded-xl border border-slate-100 text-xs">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Building</span>
                                <span class="font-bold text-slate-700 truncate block">{{ $unit->house->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-bold uppercase">Rent Amount</span>
                                <span class="font-mono font-bold text-slate-700 block">{{ number_format($unit->rent) }} RWF</span>
                            </div>
                        </div>

                        <!-- Actions / Lease status -->
                        <div class="flex items-center justify-between gap-4 pt-2 border-t border-slate-100">
                            <div>
                                @if ($unit->activeRentRecord)
                                    @php
                                        $endDate = \Carbon\Carbon::parse($unit->activeRentRecord->end_date);
                                        $now = \Carbon\Carbon::now();
                                        $diff = $now->diff($endDate);
                                    @endphp

                                    @if ($endDate->isPast())
                                        <span class="text-[10px] font-bold text-rose-600">Lease Expired</span>
                                    @else
                                        <span class="text-[10px] font-bold text-blue-600">{{ $diff->m }}m {{ $diff->d }}d remaining</span>
                                    @endif
                                @else
                                    <span class="text-[10px] text-slate-400 font-semibold">Vacant</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if (!$unit->activeRentRecord)
                                    <button wire:click="editUnit({{ $unit->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition duration-150">
                                        <i class="fas fa-edit text-[10px]"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button wire:click="confirmDeleteUnit({{ $unit->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition duration-150">
                                        <i class="fas fa-trash text-[10px]"></i>
                                        <span>Delete</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty state -->
            <div class="py-12 px-6 text-center">
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-3">
                        <i class="fas fa-door-open text-lg"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-600">No Units Found</p>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Please filter by another building or click "Add Unit" to list.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-4">
        {{ $units->links() }}
    </div>

    <!-- Add/Edit Unit Modal -->
    @if ($showUnitModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeUnitModal"></div>
            
            <!-- Modal Card -->
            <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-2xl w-full border border-slate-100 transition-all transform animate-in fade-in zoom-in-95 duration-150"
                role="dialog" aria-modal="true">
                
                <form wire:submit.prevent="storeUnit">
                    <!-- Header -->
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-base font-bold text-uds-navy">
                            {{ $unit_id ? 'Edit Unit Details' : 'Create New Unit' }}
                        </h3>
                        <button type="button" wire:click="closeUnitModal" class="text-slate-400 hover:text-slate-600 transition">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Selected Property -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Land Property (Read Only)
                                </label>
                                <select wire:model.defer="selected_property_id" 
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed"
                                    disabled>
                                    <option value="">Select Property</option>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selected House/Building -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Building (Read Only)
                                </label>
                                <select wire:model.defer="selected_house_id" 
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed"
                                    disabled>
                                    <option value="">Select House</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Unit Name -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="unit_name">
                                    Unit Name / Nameplate <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="unit_name" type="text"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="unit_name" placeholder="e.g. Unit 101, Suite A">
                                @error('unit_name')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Room Number -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="roomNumber">
                                    Room Number <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="roomNumber" type="text"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="roomNumber" placeholder="e.g. R-101">
                                @error('roomNumber')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Rent Amount -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="rent">
                                    Rent Amount (RWF) <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="rent" type="number"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="rent" placeholder="e.g. 150000">
                                @error('rent')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Rent Type / Frequency -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="rentTypes">
                                    Rent Frequency <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="rentTypes"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="rentTypes">
                                    <option value="">Select Frequency</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="daily">Daily</option>
                                </select>
                                @error('rentTypes')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unit Type -->
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="type">
                                    Unit Type / Category <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="type"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="type">
                                    <option value=" Apartment">Apartment</option>
                                    <option value="commercial">Commercial</option>
                                </select>
                                @error('type')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Floor Level (If applicable) -->
                        @if ($houseFloor)
                            <div>
                                <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="floor">
                                    Floor Level <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="floor"
                                    class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="floor">
                                    <option value="">-- Select Floor --</option>
                                    @for ($i = 0; $i <= $houseFloor; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('floor')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <!-- Description/Notes -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="notes">
                                Unit Notes / Description
                            </label>
                            <textarea wire:model="notes"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="notes" rows="2" placeholder="e.g. Backside room, renovated kitchen, etc."></textarea>
                        </div>

                        <!-- Amenities Section -->
                        <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Amenities Inventory
                                </label>
                                <div class="flex items-center gap-2">
                                    <input wire:model="newAmenityName" type="text" 
                                        class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg focus:border-uds-orange transition"
                                        placeholder="Add custom amenity name">
                                    <button type="button" wire:click="addAmenity"
                                        class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition duration-150">
                                        Add
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Amenities Checkboxes -->
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                @foreach ($allAmenities as $amenity)
                                    <label class="flex items-center text-xs font-semibold text-slate-600 cursor-pointer">
                                        <input type="checkbox" wire:model="selectedAmenities"
                                            value="{{ $amenity->name }}" 
                                            class="mr-2 rounded border-slate-300 text-uds-blue focus:ring-uds-blue/20">
                                        <span>{{ $amenity->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('newAmenityName')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeUnitModal" 
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                            Cancel
                        </button>
                        
                        <button type="submit"
                            class="px-5 py-2 text-xs font-bold text-white bg-uds-blue hover:bg-uds-blue/90 rounded-xl transition shadow-lg shadow-blue-900/15 hover:shadow-blue-900/25">
                            {{ $unit_id ? 'Save Changes' : 'Create Unit' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmingUnitDeletion)
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
                <h3 class="mb-2 text-base font-bold text-uds-navy">Delete Unit</h3>
                <p class="mb-6 text-xs text-slate-400 font-semibold leading-relaxed">
                    Are you sure you want to delete this unit? This action cannot be undone and will release any tenant assignments.
                </p>
                
                <!-- Controls -->
                <div class="flex items-center justify-center gap-3">
                    <button type="button" wire:click="cancelDelete"
                        class="w-full py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 rounded-xl transition duration-150">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteUnitConfirmed"
                        class="w-full py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition duration-150 shadow-lg shadow-red-600/15">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
