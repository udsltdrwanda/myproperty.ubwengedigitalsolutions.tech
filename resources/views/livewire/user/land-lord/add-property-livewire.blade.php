@if ($isOpen)
    <div class="fixed inset-0 z-50 p-3 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form wire:submit.prevent="store">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="mb-4 text-lg font-medium leading-6 text-gray-900">
                            {{ $property_id ? 'Edit Property' : 'Create Property' }}
                        </h3>
                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
                                    UPI <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="upi" type="text"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="upi">
                                @error('upi')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
                                    Property Name
                                </label>
                                <input wire:model="name" type="text"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="name">
                                @error('name')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Location hierarchy -->
                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="province_id">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="province_id" wire:change="loadDistricts"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="province_id">
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="district_id">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="district_id" wire:change="loadSectors"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="district_id" {{ empty($districts) ? 'disabled' : '' }}>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="sector_id">
                                    Sector <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="sector_id" wire:change="loadCells"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="sector_id" {{ empty($sectors) ? 'disabled' : '' }}>
                                    @foreach ($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                @error('sector_id')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="cell_id">
                                    Cell <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="cell_id" wire:change="loadVillages"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="cell_id" {{ empty($cells) ? 'disabled' : '' }}>
                                    @foreach ($cells as $cell)
                                        <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                                    @endforeach
                                </select>
                                @error('cell_id')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="village_id">
                                    Village <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="village_id"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="village_id" {{ empty($villages) ? 'disabled' : '' }}>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                                @error('village_id')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="property_use">
                                    Land use <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="property_use"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="property_use">
                                    <option value="Residential">Residential</option>
                                    <option value="Agricultural">Agricultural</option>
                                    <option value="Commercial">Commercial</option>
                                </select>
                                @error('property_use')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="area">
                                    Size(Area) <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="area" type="number"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="area">
                                @error('area')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="owned_year">
                                    Registration Date
                                </label>
                                <input wire:model="owned_year" type="date"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="owned_year">
                                @error('owned_year')
                                    <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="block mb-2 text-sm font-bold text-gray-700" for="description">
                                Description
                            </label>
                            <textarea wire:model="description"
                                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                id="description" rows="3"></textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message ?? '' }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="justify-between px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-button2 color="blue" action="store" size="lg" >
                            Save
                        </x-button2>

                        <x-button2 action="closeModal" color="gray" size="lg">
                            Cancel
                        </x-button2>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
