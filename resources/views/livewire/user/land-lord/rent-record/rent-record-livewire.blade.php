<div>
    <!-- Create Button -->
    <div class="justify-between my-4 d-flex">
        <x-button2 color="blue" action="openCreateModal" icon="fas fa-add"
            class="px-4 py-2 text-white bg-blue-600 rounded">
            Create Rent Record
        </x-button2>

        <a href="{{ route('landlord.import.Contract.index') }}" class="px-4 py-2 text-white bg-blue-600 rounded">
            Import
        </a>
    </div>
    <!-- Rent Records Table -->
    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr class="text-left bg-gray-100">
                    <th class="px-4 py-2">Tenant</th>
                    <th class="px-4 py-2">House</th>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Rent</th>
                    <th class="px-4 py-2">Time</th>
                    <th class="px-4 py-2">Agreement</th>
                    <th class="px-4 py-2">Invoice</th>
                    <th class="px-4 py-2">Invoice Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentRecords as $record)
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $record->tenant->tenant_name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $record->unit->house->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $record->unit->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            {{ number_format($record->amount * $record->duration_time + $record->vat * $record->duration_time, 2) }}
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($record->start_date)->format('d m Y') }} <br> -
                            {{ \Carbon\Carbon::parse($record->end_date)->format('d m Y') }} (
                            {{ $record->duration_time }} {{ rtrim(ucfirst($record->unit->rentTypes), 'ly') }}
                            )</td>
                        <td class="px-4 py-2 text-center">
                            @if ($record->agreement_document)
                                <a href="{{ $record->agreement_document }}" target="_blank" title="View Agreement">
                                    <i class="text-xl text-red-600 fa fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="text-gray-400">No File</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if ($this->hasInvoice($record->id))
                                <div class="space-y-1">
                                    @foreach ($record->invoices as $invoice)
                                        <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                                            class="block px-3 py-1 text-sm text-green-500 rounded hover:bg-blue-700">
                                            {{ $invoice->invoice_no }} ({{ $invoice->duration_units }} months)
                                        </a>
                                    @endforeach

                                    @if ($this->canCreateInvoice($record->id))
                                        <button wire:click="createInvoiceModel({{ $record->id }})"
                                            class="block w-full px-3 py-1 mt-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            title="Create Additional Invoice">
                                            <i class="mr-1 fa fa-file-invoice-dollar"></i>
                                            Create Invoice ({{ $this->getRemainingMonths($record->id) }} months remaining)
                                        </button>
                                    @endif
                                </div>
                            @else
                                <button wire:click="createInvoiceModel({{ $record->id }})"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    title="Create Invoice">
                                    <i class="mr-1 fa fa-file-invoice-dollar"></i>
                                    Create Invoice ({{ $record->duration_time }} months)
                                </button>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if ($this->hasInvoice($record->id))
                                <div class="space-y-1">
                                    @foreach ($record->invoices as $invoice)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $invoice->invoice_status === 'Paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $invoice->invoice_status === 'Partial' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $invoice->invoice_status === 'Pending' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $invoice->invoice_status === 'Canceled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $invoice->invoice_no }}: {{ $invoice->invoice_status }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($this->hasInvoice($record->id))
                                <div class="space-y-2">
                                    @foreach ($record->invoices as $invoice)
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="confirmSendEmail({{ $invoice->id }})"
                                                class="text-blue-600" title="Send Invoice PDF via Email">
                                                @if ($invoice->email_sent_count > 0)
                                                    <span class="flex items-center">
                                                        <i class="mr-1 fas fa-paper-plane"></i>
                                                        Resend ({{ $invoice->email_sent_count }})
                                                    </span>
                                                @else
                                                    <i class="fas fa-paper-plane"></i>
                                                @endif
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <button wire:click="openEditModal({{ $record->id }})" class="text-blue-600"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button wire:click="confirmDelete({{ $record->id }})" class="ml-2 text-red-600"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Send Email Confirmation Modal -->
    @if ($showSendEmailModal)
        @php
            $invoice = App\Models\Invoice::find($emailInvoiceId);
            $isResend = $invoice && $invoice->email_sent_count > 0;
        @endphp

        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative z-50 w-full max-w-sm p-6 bg-white rounded shadow-lg">

                <div class="">
                    <h2 class="mb-2 text-lg font-semibold">
                        {{ $isResend ? 'Resend Invoice' : 'Send Invoice' }} {{ $invoice->invoice_no }}
                    </h2>

                    @if ($isResend)
                        <div class="p-2 mb-3 text-sm rounded bg-blue-50">
                            Previously sent {{ $invoice->email_sent_count }} time(s)<br>
                            Last:
                            @if ($invoice->last_email_sent_at)
                                {{ \Carbon\Carbon::parse($invoice->last_email_sent_at)->format('m d, Y h:i A') }}
                            @else
                                Never
                            @endif
                        </div>
                    @endif
                    <p>Send invoice PDF to {{ $invoice->tenant->email ?? 'tenant' }}?</p>

                    <div class="flex justify-end mt-4">
                        <button wire:click="$set('showSendEmailModal', false)"
                            class="px-4 py-1 mr-2 text-white bg-gray-400 rounded">Cancel</button>

                        <x-button2 color="blue" action="sendInvoiceEmail" icon="fas fa-add"
                            class="px-4 py-2 text-white bg-blue-600 rounded">
                            <i class="mr-1 fas {{ $isResend ? 'fa-redo' : 'fa-paper-plane' }}"></i>
                            {{ $isResend ? 'Resend' : 'Send' }}
                        </x-button2>
                    </div>
                </div>
            </div>
        </div>
    @endif




    <!-- Form Modal -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div class="z-50 w-full max-w-xl p-6 bg-white rounded shadow-lg">
                    <h2 class="mb-4 text-xl font-semibold">{{ $isEdit ? 'Edit Rent Record' : 'Create Rent Record' }}
                    </h2>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                            <div>
                                <label for="tenant_id">Tenant</label>
                                <select wire:model.live="tenant_id" class="w-full p-2 border rounded">
                                    <option value="">Select</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }}</option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <!-- House selection (new) -->
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="house_id">
                                    House *
                                </label>
                                <select wire:model.live="house_id" id="house_id"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    {{ !$tenant_id ? 'disabled' : '' }}>
                                    <option value="">Select House</option>
                                    @foreach ($houses as $house)
                                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                                    @endforeach
                                </select>
                                @error('house_id')
                                    <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <!-- Unit selection -->
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700" for="unit_id">
                                    Unit *
                                </label>
                                <select wire:model.live="unit_id" id="unit_id"
                                    class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    {{ !$house_id ? 'disabled' : '' }}>
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="amount">Amount ( {{ ucfirst($rentTypes) }})</label>
                                <input type="number" wire:model="amount" readonly step="0.01"
                                    class="w-full p-2 border rounded" />
                            </div>

                            <div>
                                <label for="vat">VAT ( {{ ucfirst($rentTypes) }})</label>
                                <input type="text" wire:model="vat" readonly
                                    class="w-full p-2 bg-gray-100 border rounded" />
                            </div>
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" wire:model.live="start_date"
                                    class="w-full p-2 border rounded {{ $startDateFromLastRecord ? 'bg-gray-100' : '' }}"
                                    {{ !$tenant_id || !$unit_id || $startDateFromLastRecord ? 'disabled' : '' }} />
                                @if ($startDateFromLastRecord)
                                    <span class="text-xs text-blue-500">Date from previous record</span>
                                @endif
                            </div>

                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" wire:model.live="end_date" class="w-full p-2 border rounded"
                                    {{ !$start_date ? 'disabled' : '' }} min="{{ $start_date }}" />
                                @error('end_date')
                                    <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="duration_time">Duration Time ({{ ucfirst($rentTypes) }})</label>
                                <input type="text" wire:model="duration_time" readonly
                                    class="w-full p-2 bg-gray-100 border rounded" />
                            </div>

                            <div>
                                <label for="agreement_document">Agreement Document (PDF)</label>
                                <input type="file" id="agreement_document" wire:model="agreement_document"
                                    class="w-full border rounded" accept=".pdf,.doc,.docx" required />
                                @error('agreement_document')
                                    <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                            <div>
                                @if ($isEdit && $agreement_document && is_string($agreement_document))
                                    <div class="mt-2">
                                        <a href="{{ $agreement_document }}" target="_blank"
                                            class="text-blue-500 underline">View Existing Agreement</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-end gap-5 mt-4">
                            <button type="button" wire:click="$set('showFormModal', false)"
                                class="px-4 py-1 mr-2 text-white bg-gray-400 rounded">
                                Cancel
                            </button>

                            <button type="submit"
                                class="px-6 py-2 mb-1 mr-1 text-sm font-bold text-white uppercase transition-all duration-150 ease-linear bg-green-500 rounded shadow outline-none active:bg-green-600 hover:shadow-lg focus:outline-none">
                                {{ $isEdit ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
            <div class="z-50 w-full max-w-sm p-6 bg-white rounded shadow-lg">
                <h2 class="mb-4 text-lg font-semibold">Confirm Deletion</h2>
                <p>Are you sure you want to delete this rent record?</p>
                <div class="flex justify-end mt-4">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 mr-2 text-white bg-gray-400 rounded">Cancel</button>
                    <button wire:click="deleteConfirmed"
                        class="px-4 py-2 text-white bg-red-600 rounded">Delete</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative z-50 w-full max-w-2xl p-6 mx-auto my-6 bg-white rounded shadow-lg">
                <div class="flex items-start justify-between pb-3 border-b">
                    <h3 class="text-xl font-semibold">
                        {{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}
                    </h3>
                    <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="createInvoice" method="POST">
                    <div class="mt-4">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="invoice_no">
                                        Invoice Number *
                                    </label>
                                    <input wire:model="invoice_no" id="invoice_no" type="text"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        readonly>
                                    @error('invoice_no')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="property_id">
                                        Property *
                                    </label>
                                    <select id="property_id"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        disabled>
                                        <option value="">Select Property</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                @if ($property_id == $property->id) selected @endif>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="property_id" value="{{ $property_id }}">
                                    @error('property_id')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="house_id">
                                        House *
                                    </label>
                                    <select wire:model.live="house_id" id="house_id"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        disabled>
                                        <option value="">Select House</option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->id }}"
                                                @if ($house_name == $house->id) selected @endif>
                                                {{ $house->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('house_id')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="unit_id">
                                        Unit *
                                    </label>
                                    <select wire:model.live="unit_id" id="unit_id"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        disabled>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                @if ($unit_name == $unit->name) selected @endif>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="tenant_id">
                                        Tenant *
                                    </label>
                                    <select wire:model="tenant_id" id="tenant_id"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        disabled>
                                        <option value="">Select Tenant</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}"
                                                @if ($tenant_name == $tenant->tenant_name) selected @endif>
                                                {{ $tenant->tenant_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Contact Start Date</label>
                                    <input type="date" wire:model="start_date" class="w-full p-2 border rounded"
                                        readonly />
                                    @error('start_date')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">Contract End Date</label>
                                    <input type="date" wire:model="end_date" class="w-full p-2 border rounded"
                                        readonly />
                                    @error('end_date')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="amount">
                                        Amount * {{ $rentTypes }}
                                    </label>
                                    <input wire:model="amount" id="amount" type="number" step="0.01"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                        readonly>
                                    @error('amount')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium">VAT (18%)</label>
                                    <input type="number" wire:model="vat" readonly
                                        class="w-full p-2 text-gray-600 bg-gray-100 border rounded">
                                    @error('vat')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700" for="invoiced_months">
                                        Invoiced Months *
                                    </label>
                                    <select wire:model.live="invoiced_months" id="invoiced_months"
                                        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                                        <option value="">select</option>
                                        @for ($i = 1; $i <= $duration_time; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('invoiced_months')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Payment Due Date</label>
                                    <input type="date" wire:model="due_date" class="w-full p-2 border rounded" />
                                    @error('due_date')
                                        <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700">
                                    Invoiced Amount <br>
                                    (Amount: {{ number_format($amount, 2) }} + VAT: {{ number_format($vat, 2) }}) ×
                                    {{ $invoiced_months }} months
                                </label>
                                <input type="text" wire:model="invoiced_amount" readonly
                                    class="w-full px-3 py-2 text-gray-700 bg-gray-100 border rounded shadow focus:outline-none focus:shadow-outline" />
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end gap-4 pt-4 mt-4 border-t">
                        <button wire:click="closeModal()" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif



</div>
