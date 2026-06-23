<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)] overflow-hidden">
    <!-- Header Controls Panel -->
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-slate-50/50 to-transparent">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-uds-navy">Land Properties</h2>
                <span class="px-2.5 py-0.5 text-xs font-bold text-uds-blue bg-blue-50 rounded-full border border-blue-100/50">
                    {{ $properties->total() }} Total
                </span>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-1">Manage and track your registered land parcels, UPIs, and locations.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Import Button -->
            <a href="{{ route('landlord.import.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-xl hover:bg-slate-200 transition duration-150">
                <i class="fas fa-file-import text-slate-500"></i>
                <span>Import Properties</span>
            </a>
            
            <!-- Export Excel -->
            <button wire:click="exportToExcel"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition duration-150">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span>Export Excel</span>
            </button>
            
            <!-- Export PDF -->
            <button wire:click="exportToPdf"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-red-800 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition duration-150">
                <i class="fas fa-file-pdf text-red-600"></i>
                <span>Export PDF</span>
            </button>
            
            <!-- Add New Button -->
            <button wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-uds-orange hover:bg-uds-orange/90 rounded-xl transition duration-150 shadow-lg shadow-orange-600/15 hover:shadow-orange-600/25">
                <i class="fas fa-plus"></i>
                <span>Add Property</span>
            </button>
        </div>
    </div>

    <!-- Responsive Layouts -->
    
    <!-- 1. Desktop Table View (Visible on Medium screens and above) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100">
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider">UPI / Name</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider">District / Sector</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Cell / Village</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Land Use</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Area (Size)</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($properties as $property)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <!-- UPI & Name -->
                        <td class="py-4 px-6">
                            <span class="block font-mono text-xs font-extrabold text-uds-navy tracking-tight">
                                {{ $property->upi }}
                            </span>
                            @if($property->name)
                                <span class="block text-[10px] text-slate-400 font-semibold mt-0.5">
                                    {{ $property->name }}
                                </span>
                            @else
                                <span class="block text-[10px] text-slate-300 font-medium italic mt-0.5">
                                    No custom name
                                </span>
                            @endif
                        </td>
                        
                        <!-- District & Sector -->
                        <td class="py-4 px-6 text-xs">
                            <span class="block font-bold text-slate-700">
                                {{ optional($property->districtRelation)->name ?? 'N/A' }}
                            </span>
                            <span class="block text-[10px] text-slate-400 font-medium mt-0.5">
                                {{ optional($property->sectorRelation)->name ?? 'N/A' }}
                            </span>
                        </td>
                        
                        <!-- Cell & Village -->
                        <td class="py-4 px-6 text-xs">
                            <span class="block font-bold text-slate-700">
                                {{ optional($property->cellRelation)->name ?? 'N/A' }}
                            </span>
                            <span class="block text-[10px] text-slate-400 font-medium mt-0.5">
                                {{ optional($property->villageRelation)->name ?? 'N/A' }}
                            </span>
                        </td>
                        
                        <!-- Land Use badge -->
                        <td class="py-4 px-6 text-center">
                            @if($property->property_use === 'Residential')
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                    <i class="fas fa-home mr-1.5 text-[9px]"></i>Residential
                                </span>
                            @elseif($property->property_use === 'Commercial')
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-blue-50 text-blue-700 border border-blue-200/50">
                                    <i class="fas fa-store mr-1.5 text-[9px]"></i>Commercial
                                </span>
                            @elseif($property->property_use === 'Agricultural')
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200/50">
                                    <i class="fas fa-leaf mr-1.5 text-[9px]"></i>Agricultural
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-slate-50 text-slate-700 border border-slate-200/50">
                                    {{ $property->property_use }}
                                </span>
                            @endif
                        </td>
                        
                        <!-- Area -->
                        <td class="py-4 px-6 text-right">
                            <span class="font-mono text-xs font-bold text-uds-navy">
                                {{ number_format($property->area) }} <span class="text-[10px] text-slate-400 font-bold">m²</span>
                            </span>
                        </td>
                        
                        <!-- Actions -->
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('landlord.properties.show', $property->id) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-uds-blue/5 hover:bg-uds-blue text-uds-blue hover:text-white rounded-lg text-xs font-bold transition duration-150">
                                    <i class="fas fa-eye text-[10px]"></i>
                                    <span>View</span>
                                </a>
                                
                                <button wire:click="edit({{ $property->id }})"
                                    class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg border border-slate-200 transition duration-150"
                                    title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                
                                @if ($property->houses->isEmpty())
                                    <button wire:click="confirmDelete({{ $property->id }})"
                                        class="p-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg border border-rose-200 transition duration-150"
                                        title="Delete">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                                    <i class="fas fa-building text-xl"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-400">No properties registered</p>
                                <p class="text-[10px] text-slate-300 mt-0.5">Click "Add Property" to register a land asset.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- 2. Mobile Responsive Grid View (Visible on screens below md size) -->
    <div class="block md:hidden p-4 space-y-4">
        @forelse ($properties as $property)
            <div class="p-4 bg-slate-50/50 border border-slate-150 rounded-2xl space-y-3.5">
                <!-- Top Row: UPI & Use -->
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="block font-mono text-xs font-extrabold text-uds-navy tracking-tight">
                            {{ $property->upi }}
                        </span>
                        @if($property->name)
                            <span class="block text-[10px] text-slate-500 font-bold mt-0.5">
                                {{ $property->name }}
                            </span>
                        @endif
                    </div>
                    
                    @if($property->property_use === 'Residential')
                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                            Residential
                        </span>
                    @elseif($property->property_use === 'Commercial')
                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold rounded-md bg-blue-50 text-blue-700 border border-blue-200/50">
                            Commercial
                        </span>
                    @elseif($property->property_use === 'Agricultural')
                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold rounded-md bg-amber-50 text-amber-700 border border-amber-200/50">
                            Agricultural
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold rounded-md bg-slate-100 text-slate-700">
                            {{ $property->property_use }}
                        </span>
                    @endif
                </div>
                
                <!-- Details -->
                <div class="grid grid-cols-2 gap-3 text-[11px] font-semibold text-slate-500 pt-1 border-t border-slate-100">
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Location</p>
                        <p class="text-slate-700 mt-0.5">
                            <i class="fas fa-map-marker-alt text-slate-400 mr-1"></i>
                            {{ optional($property->districtRelation)->name ?? 'N/A' }}, {{ optional($property->sectorRelation)->name ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Area Size</p>
                        <p class="text-slate-700 mt-0.5">
                            <i class="fas fa-ruler-combined text-slate-400 mr-1"></i>
                            {{ number_format($property->area) }} m²
                        </p>
                    </div>
                </div>
                
                <!-- Action Controls -->
                <div class="flex items-center justify-end gap-2 pt-2.5 border-t border-slate-100">
                    <a href="{{ route('landlord.properties.show', $property->id) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-uds-blue/5 hover:bg-uds-blue text-uds-blue hover:text-white rounded-lg text-xs font-bold transition duration-150">
                        <i class="fas fa-eye text-[10px]"></i>
                        <span>View</span>
                    </a>
                    
                    <button wire:click="edit({{ $property->id }})"
                        class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg border border-slate-200 transition duration-150">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    
                    @if ($property->houses->isEmpty())
                        <button wire:click="confirmDelete({{ $property->id }})"
                            class="p-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg border border-rose-200 transition duration-150">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-xs font-semibold text-slate-400 bg-slate-50/20 rounded-xl">
                No properties registered.
            </div>
        @endforelse
    </div>

    <!-- Pagination Footer -->
    @if ($properties->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $properties->links() }}
        </div>
    @endif

    <!-- Modals (Add/Edit & Delete) -->
    @include('livewire.user.land-lord.add-property-livewire')
    @include('livewire.user.land-lord.delete-modal-livewire')
</div>
