@if ($showEbmInvoiceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
        <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>

        <div class="relative w-full max-w-2xl mx-auto my-6">
            <div class="relative flex flex-col w-full bg-white border-0 rounded-lg shadow-lg outline-none focus:outline-none">
                <div class="flex items-start justify-between p-5 border-b border-solid rounded-t border-blueGray-200">
                    <h3 class="text-xl font-semibold">
                        {{ $ebmInvoiceNo ? 'Edit EBM Invoice' : 'Add EBM Invoice' }}
                    </h3>
                    <button wire:click="closeEbmInvoiceModal"
                        class="float-right p-1 ml-auto text-2xl font-semibold leading-none text-black bg-transparent border-0 outline-none focus:outline-none">
                        <span class="block w-6 h-6 text-2xl">×</span>
                    </button>
                </div>

                <div class="relative flex-auto p-6">
                    <form wire:submit.prevent="saveEbmInvoice">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">EBM Invoice Number</label>
                                <input type="text" wire:model="ebmInvoiceNo" readonly
                                    class="w-full p-2 mt-1 text-gray-600 bg-gray-100 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ebmInvoiceNo')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="ebmInvoiceDescription"
                                    class="w-full p-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    rows="3"></textarea>
                                @error('ebmInvoiceDescription')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Attachment (PDF)</label>
                                <input type="file" wire:model="ebmInvoiceAttachment" accept=".pdf"
                                    class="w-full p-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ebmInvoiceAttachment')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                                @if ($ebmInvoiceAttachment && is_object($ebmInvoiceAttachment))
                                    <div class="mt-2 text-sm text-gray-600">
                                        Selected file: {{ $ebmInvoiceAttachment->getClientOriginalName() }}
                                    </div>
                                @elseif ($ebmInvoiceAttachment)
                                    <div class="mt-2 text-sm text-gray-600">
                                        Current file: {{ basename($ebmInvoiceAttachment) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model="ebmInvoiceStatus"
                                    class="w-full p-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                                @error('ebmInvoiceStatus')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end p-6 mt-4 border-t border-solid rounded-b border-blueGray-200">
                            <button type="button" wire:click="closeEbmInvoiceModal"
                                class="px-4 py-2 mr-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
