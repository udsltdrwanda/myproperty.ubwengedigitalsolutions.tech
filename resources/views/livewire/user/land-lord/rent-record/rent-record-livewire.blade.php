<div>
    <!-- Actions Toolbar (Row and Small) -->
    <div class="flex flex-row items-center gap-3 mb-6 pb-6 border-b border-slate-100">
        <button wire:click="openCreateModal"
            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-uds-orange hover:bg-uds-orange/90 rounded-xl transition duration-150 shadow-md shadow-orange-600/10 shrink-0">
            <i class="fas fa-plus"></i>
            <span>Create Rent Record</span>
        </button>

        <a href="{{ route('landlord.import.Contract.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 rounded-xl hover:bg-slate-200 transition duration-150 shrink-0">
            <i class="fas fa-file-import text-slate-500"></i>
            <span>Import Contracts</span>
        </a>
    </div>

    <!-- 1. Desktop Table View (Visible on Medium screens and above) -->
    <div class="hidden md:block overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100">
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Tenant</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">House</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Unit</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Rent</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Time</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center whitespace-nowrap">Agreement</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Invoice</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Invoice Status</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($rentRecords as $record)
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <!-- Tenant -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="p-1 bg-slate-100 text-slate-500 rounded-lg text-xs shrink-0"><i class="fas fa-user"></i></span>
                                <span class="font-bold text-uds-navy text-sm">{{ $record->tenant->tenant_name ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- House -->
                        <td class="py-4 px-6 font-semibold text-slate-600 text-sm whitespace-nowrap">
                            {{ $record->unit->house->name ?? '-' }}
                        </td>

                        <!-- Unit -->
                        <td class="py-4 px-6 text-sm text-slate-600 font-semibold whitespace-nowrap">
                            Unit {{ $record->unit->name ?? '-' }}
                        </td>

                        <!-- Rent -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-extrabold text-slate-700 text-sm">
                                    {{ number_format($record->amount * $record->duration_time + $record->vat * $record->duration_time, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Total Package</span>
                            </div>
                        </td>

                        <!-- Time -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex flex-col text-xs text-slate-500">
                                <span class="flex items-center gap-1 font-semibold text-slate-600">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($record->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($record->end_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-[10px] font-extrabold text-uds-blue mt-1">
                                    ({{ $record->duration_time }} {{ rtrim(ucfirst($record->unit->rentTypes), 'ly') }})
                                </span>
                            </div>
                        </td>

                        <!-- Agreement -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            @if ($record->agreement_document)
                                <a href="{{ $record->agreement_document }}" target="_blank" 
                                    class="inline-flex items-center justify-center p-2 text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 transition duration-150" 
                                    title="View Agreement PDF">
                                    <i class="text-base fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="text-slate-400 italic text-xs">No File</span>
                            @endif
                        </td>

                        <!-- Invoice -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            @if ($this->hasInvoice($record->id))
                                <div class="space-y-1.5">
                                    @foreach ($record->invoices as $invoice)
                                        <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-100 rounded-lg hover:bg-emerald-100 transition duration-150">
                                            <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                                            <span>{{ $invoice->invoice_no }} ({{ $invoice->duration_units }} mo)</span>
                                        </a>
                                    @endforeach

                                    @if ($this->canCreateInvoice($record->id))
                                        <button wire:click="createInvoiceModel({{ $record->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-uds-blue bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition duration-150 w-full justify-center">
                                            <i class="fas fa-plus"></i>
                                            <span>Create ({{ $this->getRemainingMonths($record->id) }} mo left)</span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <button wire:click="createInvoiceModel({{ $record->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-uds-blue hover:bg-uds-blue/90 rounded-lg transition duration-150 shadow-sm"
                                    title="Create Invoice">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Create Invoice ({{ $record->duration_time }} mo)</span>
                                </button>
                            @endif
                        </td>

                        <!-- Invoice Status -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            @if ($this->hasInvoice($record->id))
                                <div class="flex flex-col gap-1.5">
                                    @foreach ($record->invoices as $invoice)
                                        <span class="inline-flex items-center w-fit px-2.5 py-0.5 text-[10px] font-bold rounded-full border
                                            {{ $invoice->invoice_status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                            {{ $invoice->invoice_status === 'Partial' ? 'bg-slate-50 text-slate-700 border-slate-100' : '' }}
                                            {{ $invoice->invoice_status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                            {{ $invoice->invoice_status === 'Canceled' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                            {{ $invoice->invoice_no }}: {{ $invoice->invoice_status }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">No Invoice</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            @if ($this->hasInvoice($record->id))
                                <div class="flex flex-col gap-1.5 items-center">
                                    @foreach ($record->invoices as $invoice)
                                        <button wire:click="confirmSendEmail({{ $invoice->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-uds-blue bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100 transition duration-150" 
                                            title="Send Invoice PDF via Email">
                                            <i class="fas fa-paper-plane"></i>
                                            @if ($invoice->email_sent_count > 0)
                                                <span>Resend ({{ $invoice->email_sent_count }})</span>
                                            @else
                                                <span>Send PDF</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEditModal({{ $record->id }})" 
                                        class="p-2 text-uds-blue bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition duration-150"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button wire:click="confirmDelete({{ $record->id }})" 
                                        class="p-2 text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 transition duration-150"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 font-semibold">
                            <i class="fas fa-folder-open text-2xl mb-2 block"></i>
                            <span>No lease records found.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 2. Mobile Responsive Grid View (Visible on screens below md size) -->
    <div class="block md:hidden space-y-4">
        @forelse ($rentRecords as $record)
            <div class="p-5 bg-white border border-slate-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.01)] space-y-4">
                <!-- Header: Tenant & Actions -->
                <div class="flex items-start justify-between border-b border-slate-50 pb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-1 bg-slate-100 text-slate-500 rounded-lg text-xs"><i class="fas fa-user"></i></span>
                            <h4 class="font-extrabold text-uds-navy text-sm">{{ $record->tenant->tenant_name ?? '-' }}</h4>
                        </div>
                        <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">
                            {{ $record->unit->house->name ?? '-' }} • Unit {{ $record->unit->name ?? '-' }}
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if (!$this->hasInvoice($record->id))
                            <button wire:click="openEditModal({{ $record->id }})" 
                                class="p-2 text-uds-blue bg-blue-50 border border-blue-100 rounded-xl text-xs hover:bg-blue-100 transition" 
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="confirmDelete({{ $record->id }})" 
                                class="p-2 text-red-600 bg-red-50 border border-red-100 rounded-xl text-xs hover:bg-red-100 transition" 
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Total Package</span>
                        <p class="font-extrabold text-slate-700 mt-0.5">
                            {{ number_format($record->amount * $record->duration_time + $record->vat * $record->duration_time, 2) }}
                        </p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Duration</span>
                        <p class="font-bold text-uds-blue mt-0.5">
                            {{ $record->duration_time }} {{ rtrim(ucfirst($record->unit->rentTypes), 'ly') }}(s)
                        </p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Contract Duration</span>
                        <p class="text-slate-600 font-semibold mt-0.5 flex items-center gap-1.5">
                            <i class="far fa-calendar-alt text-slate-400"></i>
                            <span>{{ \Carbon\Carbon::parse($record->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($record->end_date)->format('d/m/Y') }}</span>
                        </p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Agreement</span>
                        <div class="mt-1">
                            @if ($record->agreement_document)
                                <a href="{{ $record->agreement_document }}" target="_blank" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-red-800 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                    <span>View PDF</span>
                                </a>
                            @else
                                <span class="text-slate-400 italic">No File</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Invoice Status</span>
                        <div class="mt-1 flex flex-col gap-1.5">
                            @if ($this->hasInvoice($record->id))
                                @foreach ($record->invoices as $invoice)
                                    <span class="inline-flex items-center w-fit px-2 py-0.5 text-[10px] font-bold rounded-full border
                                        {{ $invoice->invoice_status === 'Paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                        {{ $invoice->invoice_status === 'Partial' ? 'bg-slate-50 text-slate-700 border-slate-100' : '' }}
                                        {{ $invoice->invoice_status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                        {{ $invoice->invoice_status === 'Canceled' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                        {{ $invoice->invoice_no }}: {{ $invoice->invoice_status }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-slate-400 italic">No Invoice</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Actions -->
                <div class="pt-3 border-t border-slate-50 space-y-3">
                    @if ($this->hasInvoice($record->id))
                        <div class="flex flex-col gap-2">
                            @foreach ($record->invoices as $invoice)
                                <div class="flex items-center justify-between text-xs">
                                    <a href="{{ route('landlord.invoice.detail', $invoice->id) }}" class="font-bold text-uds-blue hover:underline">
                                        {{ $invoice->invoice_no }} ({{ $invoice->duration_units }} months) →
                                    </a>
                                    <button wire:click="confirmSendEmail({{ $invoice->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold text-uds-blue bg-blue-50 border border-blue-100 rounded-lg hover:bg-blue-100" 
                                        title="Send Email">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>{{ $invoice->email_sent_count > 0 ? 'Resend' : 'Send PDF' }}</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        @if ($this->canCreateInvoice($record->id))
                            <button wire:click="createInvoiceModel({{ $record->id }})" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-uds-blue bg-blue-50 border border-blue-100 rounded-xl w-full justify-center">
                                <i class="fas fa-plus"></i>
                                <span>Create Additional Invoice ({{ $this->getRemainingMonths($record->id) }} mo remaining)</span>
                            </button>
                        @endif
                    @else
                        <button wire:click="createInvoiceModel({{ $record->id }})" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-uds-blue rounded-xl w-full justify-center">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Create Invoice ({{ $record->duration_time }} months)</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-slate-400 bg-white border border-slate-100 rounded-2xl">
                <i class="fas fa-folder-open text-xl mb-1 block"></i>
                <span>No lease records found.</span>
            </div>
        @endforelse
    </div>

    <!-- Send Email Confirmation Modal -->
    @if ($showSendEmailModal)
        @php
            $invoice = App\Models\Invoice::find($emailInvoiceId);
            $isResend = $invoice && $invoice->email_sent_count > 0;
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 transition-opacity" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" wire:click="$set('showSendEmailModal', false)"></div>
            
            <div class="relative w-full max-w-md bg-white rounded-2xl border border-slate-100 shadow-2xl overflow-hidden p-6">
                <div>
                    <h2 class="text-lg font-bold text-uds-navy mb-2">
                        {{ $isResend ? 'Resend Invoice' : 'Send Invoice' }} {{ $invoice->invoice_no }}
                    </h2>

                    @if ($isResend)
                        <div class="p-3 mb-4 text-xs rounded-xl bg-blue-50 text-uds-blue font-semibold">
                            Previously sent {{ $invoice->email_sent_count }} time(s)<br>
                            Last: {{ $invoice->last_email_sent_at ? \Carbon\Carbon::parse($invoice->last_email_sent_at)->format('d/m/Y h:i A') : 'Never' }}
                        </div>
                    @endif
                    
                    <p class="text-sm text-slate-500">Send invoice PDF to tenant email: <span class="font-bold text-slate-700">{{ $invoice->tenant->email ?? 'tenant' }}</span>?</p>

                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="$set('showSendEmailModal', false)"
                            class="px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                            Cancel
                        </button>

                        <x-button2 color="blue" action="sendInvoiceEmail" icon="fas fa-paper-plane"
                            colorClasses="bg-uds-blue text-white hover:bg-uds-blue/90"
                            sizeClasses="px-4 py-2 text-xs"
                            class="font-bold rounded-xl transition">
                            {{ $isResend ? 'Resend' : 'Send Now' }}
                        </x-button2>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Create / Edit Rent Record Form Modal -->
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 transition-opacity" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" wire:click="$set('showFormModal', false)"></div>
            
            <div class="relative w-full max-w-2xl bg-white rounded-2xl border border-slate-100 shadow-2xl overflow-hidden my-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-extrabold text-uds-navy uppercase tracking-wide">
                        {{ $isEdit ? 'Edit Rent Record' : 'Create Rent Record' }}
                    </h3>
                    <button wire:click="$set('showFormModal', false)" class="text-slate-400 hover:text-slate-600 transition">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tenant -->
                        <div>
                            <label for="tenant_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tenant *</label>
                            <select wire:model.live="tenant_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150">
                                <option value="">Select Tenant</option>
                                @foreach ($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }}</option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- House -->
                        <div>
                            <label for="house_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">House *</label>
                            <select wire:model.live="house_id" id="house_id"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 {{ !$tenant_id ? 'bg-slate-50 cursor-not-allowed text-slate-400 border-slate-200' : '' }}"
                                {{ !$tenant_id ? 'disabled' : '' }}>
                                <option value="">Select House</option>
                                @foreach ($houses as $house)
                                    <option value="{{ $house->id }}">{{ $house->name }}</option>
                                @endforeach
                            </select>
                            @error('house_id')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div>
                            <label for="unit_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Unit *</label>
                            <select wire:model.live="unit_id" id="unit_id"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 {{ !$house_id ? 'bg-slate-50 cursor-not-allowed text-slate-400 border-slate-200' : '' }}"
                                {{ !$house_id ? 'disabled' : '' }}>
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Amount ({{ ucfirst($rentTypes) }})</label>
                            <input type="number" wire:model="amount" readonly step="0.01"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-500 cursor-not-allowed" />
                        </div>

                        <!-- VAT -->
                        <div>
                            <label for="vat" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">VAT ({{ ucfirst($rentTypes) }})</label>
                            <input type="text" wire:model="vat" readonly
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-500 cursor-not-allowed" />
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Start Date</label>
                            <input type="date" wire:model.live="start_date"
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 {{ !$tenant_id || !$unit_id || $startDateFromLastRecord ? 'bg-slate-50 cursor-not-allowed text-slate-500' : '' }}"
                                {{ !$tenant_id || !$unit_id || $startDateFromLastRecord ? 'disabled' : '' }} />
                            @if ($startDateFromLastRecord)
                                <span class="text-[10px] text-uds-blue font-bold mt-1 block">Date carried over from last record</span>
                            @endif
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">End Date</label>
                            <input type="date" wire:model.live="end_date" 
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 {{ !$start_date ? 'bg-slate-50 cursor-not-allowed' : '' }}"
                                {{ !$start_date ? 'disabled' : '' }} min="{{ $start_date }}" />
                            @error('end_date')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Duration Time -->
                        <div>
                            <label for="duration_time" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Duration ({{ ucfirst($rentTypes) }})</label>
                            <input type="text" wire:model="duration_time" readonly
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-500 cursor-not-allowed" />
                        </div>

                        <!-- Agreement Document Upload -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="agreement_document" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Agreement Document (PDF)</label>
                            <input type="file" id="agreement_document" wire:model="agreement_document"
                                class="w-full px-3 py-1.5 text-xs text-slate-500 border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-extrabold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer" 
                                accept=".pdf,.doc,.docx" required />
                            @error('agreement_document')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror

                            @if ($isEdit && $agreement_document && is_string($agreement_document))
                                <div class="mt-2.5 flex items-center gap-1">
                                    <i class="fas fa-file-pdf text-red-500 text-xs"></i>
                                    <a href="{{ $agreement_document }}" target="_blank"
                                        class="text-xs text-uds-blue font-bold hover:underline">View existing document</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showFormModal', false)"
                            class="px-5 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-6 py-2.5 text-xs font-bold text-white uppercase tracking-wider bg-uds-orange hover:bg-uds-orange/90 rounded-xl shadow-lg transition">
                            {{ $isEdit ? 'Update Record' : 'Save Record' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 transition-opacity" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" wire:click="$set('showDeleteModal', false)"></div>
            
            <div class="relative w-full max-w-sm bg-white rounded-2xl border border-slate-100 shadow-2xl p-6 text-center">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <h2 class="text-base font-extrabold text-uds-navy uppercase tracking-wide mb-2">Confirm Deletion</h2>
                <p class="text-sm text-slate-500 mb-6">Are you sure you want to delete this rent agreement record? This action cannot be undone.</p>
                
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                        Cancel
                    </button>
                    <button wire:click="deleteConfirmed"
                        class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition shadow-md shadow-red-600/10">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Create/Edit Invoice Modal (max-w-xl w-full with inline blurry backdrop) -->
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <!-- Backdrop Blur Overlay -->
            <div class="fixed inset-0 bg-slate-900/40 transition-opacity" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);" wire:click="closeModal()"></div>
            
            <!-- Modal Card -->
            <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden max-w-xl w-full border border-slate-100 transition-all transform animate-in fade-in zoom-in-95 duration-150 my-6"
                role="dialog" aria-modal="true">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-extrabold text-uds-navy uppercase tracking-wide">
                        {{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}
                    </h3>
                    <button wire:click="closeModal()" type="button" class="text-slate-400 hover:text-slate-600 transition">
                        <span class="text-xl">&times;</span>
                    </button>
                </div>

                <form wire:submit.prevent="createInvoice" class="p-6">
                    <div class="space-y-4">
                        <!-- Invoice Info Summary Card -->
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-2.5 text-xs">
                            <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                                <span class="font-bold text-slate-400 uppercase tracking-wider text-[9px]">Invoice Number</span>
                                <span class="font-extrabold text-uds-navy">{{ $invoice_no }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-slate-600 font-semibold">
                                <div class="col-span-2">
                                    <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider mb-0.5">Tenant</span>
                                    <span class="text-xs text-slate-800 font-bold">{{ App\Models\Tenant::find($tenant_id)->tenant_name ?? '-' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider mb-0.5">Property & Unit</span>
                                    <span class="text-xs text-slate-700 font-bold">
                                        {{ App\Models\House::find($house_id)->name ?? '-' }} • Unit {{ App\Models\PropertyUnit::find($unit_id)->name ?? '-' }}
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider mb-0.5">Contract Period</span>
                                    <span class="flex items-center gap-1 text-slate-500 font-semibold">
                                        <i class="far fa-calendar-alt text-slate-400"></i>
                                        {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : '-' }} - {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider mb-0.5">Monthly Rent</span>
                                    <span>{{ number_format(floatval($amount) ?: 0, 2) }} RWF</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider mb-0.5">VAT (18%)</span>
                                    <span>{{ number_format(floatval($vat) ?: 0, 2) }} RWF</span>
                                </div>
                            </div>
                        </div>

                        <!-- Editable Fields in 2 columns -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="invoiced_months">
                                    Invoiced Months *
                                </label>
                                <select wire:model.live="invoiced_months" id="invoiced_months"
                                    class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150">
                                    <option value="">Select Months</option>
                                    @for ($i = 1; $i <= $duration_time; $i++)
                                        <option value="{{ $i }}">
                                            {{ $i }} Month(s)
                                        </option>
                                    @endfor
                                </select>
                                @error('invoiced_months')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5" for="due_date">
                                    Payment Due Date *
                                </label>
                                <input type="date" wire:model="due_date" id="due_date"
                                    class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150" />
                                @error('due_date')
                                    <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Calculated Invoice Amount -->
                        <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex justify-between items-center gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-uds-blue uppercase tracking-wider block">Total Invoice Amount</span>
                                <span class="text-[9px] text-slate-400 mt-0.5 block">
                                    Formula: (Rent: {{ number_format(floatval($amount) ?: 0, 2) }} + VAT: {{ number_format(floatval($vat) ?: 0, 2) }}) × {{ $invoiced_months ?: 0 }} mo
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-extrabold text-uds-navy block whitespace-nowrap">{{ number_format($invoiced_amount ?: 0, 2) }} RWF</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button wire:click="closeModal()" type="button"
                            class="px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-xs font-bold text-white bg-uds-blue hover:bg-uds-blue/90 rounded-xl shadow-lg transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
