<div class="py-8 bg-gray-100">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <form action="landlord.import.store" enctype="multipart/form-data" class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-bold uppercase text-primary">File Must Be Excel</label>
                                <input type="file" wire:model="file"
                                       class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg" />
                                @error('file') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 font-semibold text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                Import
                            </button>
                        </form>
                    </div>
                    <div>
                        @if ($message = Session::get('success'))
                            <div class="p-4 mt-2 text-green-700 bg-green-100 border border-green-400 rounded shadow">
                                {{ $message }}
                                <div class="h-1 mt-2 bg-green-400 animate-pulse"></div>
                            </div>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="p-4 mt-2 text-red-700 bg-red-100 border border-red-400 rounded shadow">
                                {{ $message }}
                                <div class="h-1 mt-2 bg-red-400 animate-pulse"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
