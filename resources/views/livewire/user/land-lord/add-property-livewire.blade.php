@if ($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop Blur Overlay -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-2xl w-full border border-slate-100 transition-all transform animate-in fade-in zoom-in-95 duration-150"
            role="dialog" aria-modal="true">
            
            <form wire:submit.prevent="store">
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-base font-bold text-uds-navy">
                        {{ $property_id ? 'Edit Land Property' : 'Register New Land Property' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- UPI -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="upi">
                                UPI Number <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="upi" type="text"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="upi" placeholder="e.g. 1/02/03/04/1234">
                            @error('upi')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Property Name -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="name">
                                Property Custom Name
                            </label>
                            <input wire:model="name" type="text"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="name" placeholder="e.g. Kacyiru Commercial Plaza">
                            @error('name')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Hierarchy -->
                    <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-4">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Geographic Location</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <!-- Province -->
                            <div>
                                <label class="block mb-1.5 text-[10px] font-bold text-slate-500 uppercase" for="province_id">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="province_id" wire:change="loadDistricts"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="province_id">
                                    <option value="">Select Province</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- District -->
                            <div>
                                <label class="block mb-1.5 text-[10px] font-bold text-slate-500 uppercase" for="district_id">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="district_id" wire:change="loadSectors"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="district_id" {{ empty($districts) ? 'disabled' : '' }}>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Sector -->
                            <div>
                                <label class="block mb-1.5 text-[10px] font-bold text-slate-500 uppercase" for="sector_id">
                                    Sector <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="sector_id" wire:change="loadCells"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="sector_id" {{ empty($sectors) ? 'disabled' : '' }}>
                                    <option value="">Select Sector</option>
                                    @foreach ($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                @error('sector_id')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Cell -->
                            <div>
                                <label class="block mb-1.5 text-[10px] font-bold text-slate-500 uppercase" for="cell_id">
                                    Cell <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="cell_id" wire:change="loadVillages"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="cell_id" {{ empty($cells) ? 'disabled' : '' }}>
                                    <option value="">Select Cell</option>
                                    @foreach ($cells as $cell)
                                        <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                                    @endforeach
                                </select>
                                @error('cell_id')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Village -->
                            <div>
                                <label class="block mb-1.5 text-[10px] font-bold text-slate-500 uppercase" for="village_id">
                                    Village <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="village_id"
                                    class="w-full px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                    id="village_id" {{ empty($villages) ? 'disabled' : '' }}>
                                    <option value="">Select Village</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                                @error('village_id')
                                    <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Land Use -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="property_use">
                                Land use <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="property_use"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="property_use">
                                <option value="Residential">Residential</option>
                                <option value="Agricultural">Agricultural</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                            @error('property_use')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Area / Size -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="area">
                                Size (Area in m²) <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="area" type="number"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="area" placeholder="e.g. 500">
                            @error('area')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Owned Year / Registration Date -->
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="owned_year">
                                Registration Date
                            </label>
                            <input wire:model="owned_year" type="date"
                                class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                                id="owned_year">
                            @error('owned_year')
                                <span class="block mt-1 text-[10px] font-bold text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider" for="description">
                            Property Description / Notes
                        </label>
                        <textarea wire:model="description"
                            class="w-full px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-orange focus:ring focus:ring-uds-orange/20 transition duration-150"
                            id="description" rows="3" placeholder="Enter any extra details or annotations about this land property..."></textarea>
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
