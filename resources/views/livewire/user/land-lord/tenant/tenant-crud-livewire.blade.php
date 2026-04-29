<div class="container px-4 py-6 mx-auto">
    <div class="flex justify-between mb-4">
        <div>
            <h2 class="text-xl font-semibold">Tenants</h2>
        </div>
        <div class="flex space-x-2">
            <x-button2 color="green" action="create" icon="fas fa-plus">
                 Add Tenant
            </x-button2>
            <x-button2 color="blue" action="exportAllToPdf" icon="fas fa-file-pdf" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                Export All
           </x-button2>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full divide-y divide-gray-200 table-auto min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tenant ID</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 ">
                @forelse ($tenants as $tenant)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tenant->tenant_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tenant->tenant_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tenant->company_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="https://wa.me/{{ $tenant->phone }}" target="_blank" class="text-blue-600">
                                <i class="text-green-500 fa fa-phone"></i> {{ $tenant->phone }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="mailto:{{ $tenant->email }}" class="text-blue-600">
                                <i class="text-gray-500 fa fa-envelope"></i> {{ $tenant->email }}
                            </a>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $tenant->id }})"
                                    class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $tenant->id }})"
                                    class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">No tenants found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@include('livewire.user.land-lord.tenant.create-tenant-livewire')

</div>
