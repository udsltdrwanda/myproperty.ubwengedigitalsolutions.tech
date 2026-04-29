@if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="relative w-auto max-w-sm mx-auto my-6">
            <div class="w-full max-w-md p-6 bg-white rounded-lg">
                <div class="text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                        <i class="text-red-600 fa fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-gray-900">Delete Property
                    </h3>
                    <p class="mb-6 text-gray-500">Are you sure you want to delete this
                        property? This action cannot be undone.</p>

                    <div class="flex justify-center space-x-3">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            <i class="mr-2 fa fa-times"></i> Cancel
                        </button>
                        <button wire:click="deleteProperty"
                            class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                            <i class="mr-2 fa fa-trash-alt"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
