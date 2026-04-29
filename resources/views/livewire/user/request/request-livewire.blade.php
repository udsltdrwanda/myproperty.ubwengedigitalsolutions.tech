<!-- Flowbite Modal -->
<div id="request-modal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Request to Use System
                </h3>
                <button type="button" class="inline-flex items-center justify-center w-8 h-8 ml-auto text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="request-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l12 12m0-12L1 13"/>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 text-left">
                <div>
                    <form wire:submit.prevent="submit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" id="name" wire:model="name"
                                class="w-full p-2 mt-1 text-gray-900 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                            @error('name') <span class="text-red-500">{{ $message ?? '' }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" id="email" wire:model="email"
                                class="w-full p-2 mt-1 text-gray-900 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                            @error('email') <span class="text-red-500">{{ $message ?? '' }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="tel" id="phone" wire:model="phone"
                                class="w-full p-2 mt-1 text-gray-900 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                            @error('phone') <span class="text-red-500">{{ $message ?? '' }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
