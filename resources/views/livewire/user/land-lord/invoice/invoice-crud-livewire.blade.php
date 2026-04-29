<div class="container px-4 py-6 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Invoice Management</h1>
    </div>

    <!-- Filter section -->
    <div class="flex flex-wrap items-center justify-between gap-4 p-4 mb-4 bg-white rounded-lg shadow">
        <div class="flex items-center space-x-4">
            <div>
                <label for="status_filter" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                <select wire:model.live="status_filter" id="status_filter"
                    class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- <div>
            <x-button2 color="blue" action="create" icon="fas fa-add"
                class="px-4 py-2 text-white bg-blue-600 rounded">
                Create Invoice
            </x-button2>
        </div> --}}

        <div>
            <label for="per_page" class="block text-sm font-medium text-gray-700">Items per page</label>
            <select wire:model.live="per_page" id="per_page"
                class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full divide-y divide-gray-200 table-auto min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">House</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Unit</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Tenant</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-left text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($paginatedInvoices as $invoice)
                    @php
                        $paidAmount = $invoice->payments->sum('payed_amount');
                        $totalAmount = $invoice->amount + $invoice->vat;
                    @endphp
                    <tr>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <a href="{{ route('landlord.invoice.detail', $invoice->id) }}" class="text-blue-700">
                                {{ $invoice->invoice_no }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">{{ $invoice->unit->house->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">{{ $invoice->unit->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">{{ $invoice->duration_units ?? '-' }}
                            {{ rtrim(ucfirst($invoice->unit->rentTypes ?? ''), 'ly') }}</td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            {{ number_format($totalAmount, 2) }}</td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $invoice->invoice_status === 'Paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $invoice->invoice_status === 'Partial' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $invoice->invoice_status === 'Pending' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $invoice->invoice_status === 'Canceled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $invoice->invoice_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            <div class="flex justify-between">
                                <div>
                                    @if ($invoice->invoice_status == 'Pending')
                                        <x-button2 color="blue" size="sm" action="edit({{ $invoice->id }})"
                                            title="Edit">
                                            <i class="text-sm fa fa-edit"></i>
                                        </x-button2>
                                    @else
                                        <x-button2 disabled color="blue" size="sm"
                                            title="Cannot edit - status is not Pending">
                                            <i class="text-sm fa fa-edit"></i>
                                        </x-button2>
                                    @endif
                                </div>
                                <div>
                                    @if ($invoice->invoice_status == 'Pending')
                                        <x-button2 color="red" size="sm"
                                            action="confirmDelete({{ $invoice->id }})" title="Delete">
                                            <i class="text-sm fa fa-trash"></i>
                                        </x-button2>
                                    @else
                                        <x-button2 color="red" disabled size="sm"
                                            title="Cannot delete - status is not Pending">
                                            <i class="text-sm fa fa-trash"></i>
                                        </x-button2>
                                    @endif
                                </div>
                                <div>
                                    <x-button2 color="green" size="sm" action="generatePdf({{ $invoice->id }})"
                                        title="Download PDF">
                                        <i class="text-sm fa fa-file-pdf"></i>
                                    </x-button2>
                                </div>
                                @if($invoice->invoice_status == 'Paid')
                                <div>
                                    @if (!$invoice->ebmInvoice)
                                        <x-button2 color="blue" size="sm" action="addEbmInvoice({{ $invoice->id }})"
                                            title="Add EBM Invoice">
                                            <i class="text-sm fa fa-plus"></i>
                                        </x-button2>
                                    @else
                                        <x-button2 color="green" size="sm" action="viewEbmInvoice({{ $invoice->id }})"
                                            title="View EBM Invoice">
                                            <i class="text-sm fa fa-eye"></i>
                                        </x-button2>
                                        @if ($invoice->ebmInvoice->invoice_attachment)
                                            <x-button2 color="blue" size="sm" action="downloadEbmInvoice({{ $invoice->id }})"
                                                title="Download EBM Invoice PDF">
                                                <i class="text-sm fa fa-download"></i>
                                            </x-button2>
                                        @endif
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center">No invoices found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $paginatedInvoices->links() }}
    </div>

    <!-- Include the create invoice form modal -->
    @include('livewire.user.land-lord.invoice.create-invoice-form')

    <!-- Include the EBM invoice modal -->
    @include('livewire.user.land-lord.invoice.ebm-invoice-modal')

</div>
