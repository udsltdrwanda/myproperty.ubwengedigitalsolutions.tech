@if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <!-- Backdrop Blur Overlay -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="cancelDelete"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-sm w-full border border-slate-100 transition-all transform animate-in fade-in zoom-in-95 duration-150 p-6"
            role="dialog" aria-modal="true">
            
            <div class="text-center">
                <!-- Icon -->
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-50 text-red-600 rounded-full border border-red-100">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                
                <!-- Title -->
                <h3 class="mb-2 text-base font-bold text-[#0b2545]">Delete Property</h3>
                <p class="mb-6 text-xs text-slate-400 font-semibold leading-relaxed">
                    Are you sure you want to delete this property? This action cannot be undone and will delete all related records.
                </p>

                <!-- Actions -->
                <div class="flex justify-center gap-3">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition border border-slate-200">
                        Cancel
                    </button>
                    
                    <button wire:click="deleteProperty"
                        class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-lg shadow-red-600/15 hover:shadow-red-600/25">
                        Confirm Delete
                    </button>
                </div>
            </div>
            
        </div>
    </div>
@endif
