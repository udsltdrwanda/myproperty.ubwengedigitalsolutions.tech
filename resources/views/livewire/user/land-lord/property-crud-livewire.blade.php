<div class="min-h-screen bg-gray-50">
    <div class="container px-4 py-6 mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col items-center justify-between gap-4 mb-8 md:flex-row">
            <div class="flex items-center gap-6">
                <h2 class="text-3xl font-bold text-gray-800">My Properties</h2>
                <a href="{{ route('landlord.import.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="mr-2 fas fa-file-import"></i>
                    <span>Import Properties</span>
                </a>
            </div>
            <div class="flex flex-wrap gap-3">
                <x-button2 color="gray" size="sm" action="exportToExcel" icon="fa fa-file-excel" class="hover:bg-gray-600">
                    Export Excel
                </x-button2>
                <x-button2 color="gray" size="sm" action="exportToPdf" icon="fa fa-file-pdf" class="hover:bg-gray-600">
                    Export PDF
                </x-button2>
                <x-button2 color="green" size="sm" action="create" icon="fa fa-plus" class="hover:bg-green-600">
                    Add New Property
                </x-button2>
            </div>
        </div>
        <!-- Properties Table -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold text-gray-700 uppercase bg-gray-100">
                            <th class="px-6 py-4 text-left">UPI</th>
                            <th class="px-6 py-4 text-left">District</th>
                            <th class="px-6 py-4 text-left">Sector</th>
                            <th class="px-6 py-4 text-center">Cell</th>
                            <th class="px-6 py-4 text-left">Village</th>
                            <th class="px-6 py-4 text-left">Property Use</th>
                            <th class="px-6 py-4 text-left">Area</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($properties as $property)
                            <tr class="transition-colors duration-200 hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $property->upi }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ optional($property->districtRelation)->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ optional($property->sectorRelation)->name }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-600">{{ optional($property->cellRelation)->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ optional($property->villageRelation)->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $property->property_use }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $property->area }} m²</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('landlord.properties.show', $property->id) }}"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="mr-2 fas fa-eye"></i>
                                            View
                                        </a>

                                        <x-button2 action="edit({{ $property->id }})" size="sm" color="blue" class="hover:bg-blue-600">
                                            <i class="fa fa-edit"></i>
                                        </x-button2>

                                        @if ($property->houses->isEmpty())
                                            <x-button2 action="confirmDelete({{ $property->id }})" color="red" size="sm" class="hover:bg-red-600">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-button2>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $properties->links() }}
        </div>

        <!-- Modal Components -->
        @include('livewire.user.land-lord.add-property-livewire')
        @include('livewire.user.land-lord.delete-modal-livewire')
    </div>
</div>
