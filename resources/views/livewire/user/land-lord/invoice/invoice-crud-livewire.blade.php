<div>
    <!-- Filters & Controls Toolbar -->
    <div class="flex flex-wrap items-end justify-between gap-4 p-5 border-b border-slate-100 bg-slate-50/50">
        <div class="flex flex-wrap items-end gap-3">
            <!-- Status Filter -->
            <div>
                <label for="status_filter" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter by Status</label>
                <select wire:model.live="status_filter" id="status_filter"
                    class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150 min-w-[140px]">
                    <option value="">All Statuses</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Per Page -->
            <div>
                <label for="per_page" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Per Page</label>
                <select wire:model.live="per_page" id="per_page"
                    class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl focus:border-uds-blue focus:ring-4 focus:ring-uds-blue/5 transition duration-150">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Status Legend Pills -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl bg-amber-50 text-amber-700 border border-amber-100">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>Pending
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Paid
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl bg-blue-50 text-uds-blue border border-blue-100">
                <span class="w-1.5 h-1.5 rounded-full bg-uds-blue inline-block"></span>Partial
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl bg-rose-50 text-rose-700 border border-rose-100">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block"></span>Canceled
            </span>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-100">
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Invoice #</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">House</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Unit</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Tenant</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Duration</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Total (RWF)</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th class="py-4 px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($paginatedInvoices as $invoice)
                    @php
                        $paidAmount = $invoice->payments->sum('payed_amount');
                        $totalAmount = $invoice->amount + $invoice->vat;
                        $isPending = $invoice->invoice_status === 'Pending';
                        $isPaid    = $invoice->invoice_status === 'Paid';
                    @endphp
                    <tr class="hover:bg-slate-50/40 transition duration-150">
                        <!-- Invoice # -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                                class="inline-flex items-center gap-1.5 font-extrabold text-uds-blue hover:text-uds-navy text-xs transition duration-150">
                                <i class="fas fa-file-invoice text-uds-blue/50 text-[10px]"></i>
                                {{ $invoice->invoice_no }}
                            </a>
                        </td>

                        <!-- House -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-orange-50 text-uds-orange flex items-center justify-center text-[10px] shrink-0">
                                    <i class="fas fa-home"></i>
                                </span>
                                <span class="text-xs font-semibold text-slate-700">{{ $invoice->unit->house->name ?? 'N/A' }}</span>
                            </div>
                        </td>

                        <!-- Unit -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-700 rounded-lg">
                                <i class="fas fa-door-open text-[9px]"></i>
                                {{ $invoice->unit->name ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Tenant -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-uds-navy/10 text-uds-navy flex items-center justify-center text-[10px] font-bold shrink-0">
                                    {{ strtoupper(substr($invoice->tenant->tenant_name ?? 'T', 0, 1)) }}
                                </span>
                                <span class="text-xs font-semibold text-slate-700">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</span>
                            </div>
                        </td>

                        <!-- Duration -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-600">
                                {{ $invoice->duration_units ?? '-' }}
                                {{ rtrim(ucfirst($invoice->unit->rentTypes ?? ''), 'ly') }}
                            </span>
                        </td>

                        <!-- Total -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-extrabold text-slate-800">{{ number_format($totalAmount, 2) }}</span>
                                @if ($paidAmount > 0 && $paidAmount < $totalAmount)
                                    <span class="text-[10px] text-emerald-600 font-bold mt-0.5">Paid: {{ number_format($paidAmount, 2) }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-full border
                                {{ $invoice->invoice_status === 'Paid'     ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                {{ $invoice->invoice_status === 'Partial'  ? 'bg-blue-50 text-uds-blue border-blue-100'          : '' }}
                                {{ $invoice->invoice_status === 'Pending'  ? 'bg-amber-50 text-amber-700 border-amber-100'       : '' }}
                                {{ $invoice->invoice_status === 'Canceled' ? 'bg-rose-50 text-rose-700 border-rose-100'          : '' }}">
                                <span class="w-1 h-1 rounded-full inline-block
                                    {{ $invoice->invoice_status === 'Paid'     ? 'bg-emerald-500' : '' }}
                                    {{ $invoice->invoice_status === 'Partial'  ? 'bg-uds-blue'    : '' }}
                                    {{ $invoice->invoice_status === 'Pending'  ? 'bg-amber-500'   : '' }}
                                    {{ $invoice->invoice_status === 'Canceled' ? 'bg-rose-500'    : '' }}">
                                </span>
                                {{ $invoice->invoice_status }}
                            </span>
                        </td>

                        <!-- ── Actions ── -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5">

                                {{-- VIEW – always active, indigo/blue --}}
                                <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                           text-uds-blue bg-blue-50 border-blue-200 hover:bg-blue-100"
                                    title="View Detail">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>

                                {{-- EDIT – only shown when Pending --}}
                                @if ($isPending)
                                    <button wire:click="edit({{ $invoice->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                               text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100"
                                        title="Edit Invoice">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                @endif

                                {{-- DELETE – only shown when Pending --}}
                                @if ($isPending)
                                    <button wire:click="confirmDelete({{ $invoice->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                               text-red-600 bg-red-50 border-red-200 hover:bg-red-100"
                                        title="Delete Invoice">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                @endif

                                {{-- PDF – always active, rose/orange --}}
                                <button wire:click="generatePdf({{ $invoice->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                           text-rose-700 bg-rose-50 border-rose-200 hover:bg-rose-100"
                                    title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>PDF</span>
                                </button>

                                {{-- EBM – only shown on Paid invoices --}}
                                @if ($isPaid)
                                    @if (!$invoice->ebmInvoice)
                                        <button wire:click="addEbmInvoice({{ $invoice->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                                   text-violet-700 bg-violet-50 border-violet-200 hover:bg-violet-100"
                                            title="Add EBM Invoice">
                                            <i class="fas fa-receipt"></i>
                                            <span>EBM</span>
                                        </button>
                                    @else
                                        <button wire:click="viewEbmInvoice({{ $invoice->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                                   text-teal-700 bg-teal-50 border-teal-200 hover:bg-teal-100"
                                            title="View EBM Invoice">
                                            <i class="fas fa-eye"></i>
                                            <span>EBM</span>
                                        </button>
                                        @if ($invoice->ebmInvoice->invoice_attachment)
                                            <button wire:click="downloadEbmInvoice({{ $invoice->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition duration-150
                                                       text-indigo-700 bg-indigo-50 border-indigo-200 hover:bg-indigo-100"
                                                title="Download EBM Invoice PDF">
                                                <i class="fas fa-download"></i>
                                                <span>DL</span>
                                            </button>
                                        @endif
                                    @endif
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fas fa-file-invoice-dollar text-3xl mb-3 block text-slate-200"></i>
                            <p class="font-semibold text-sm">No invoices found</p>
                            <p class="text-xs mt-1">Try adjusting your filter or create an invoice from the Rent Records page.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden divide-y divide-slate-100">
        @forelse ($paginatedInvoices as $invoice)
            @php
                $paidAmount  = $invoice->payments->sum('payed_amount');
                $totalAmount = $invoice->amount + $invoice->vat;
                $isPending   = $invoice->invoice_status === 'Pending';
                $isPaid      = $invoice->invoice_status === 'Paid';
            @endphp
            <div class="p-5 space-y-3">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                            class="text-sm font-extrabold text-uds-blue hover:underline flex items-center gap-1">
                            <i class="fas fa-file-invoice text-xs"></i>
                            {{ $invoice->invoice_no }}
                        </a>
                        <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">
                            {{ $invoice->unit->house->name ?? 'N/A' }} • {{ $invoice->unit->name ?? 'N/A' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-full border
                        {{ $invoice->invoice_status === 'Paid'     ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                        {{ $invoice->invoice_status === 'Partial'  ? 'bg-blue-50 text-uds-blue border-blue-100'          : '' }}
                        {{ $invoice->invoice_status === 'Pending'  ? 'bg-amber-50 text-amber-700 border-amber-100'       : '' }}
                        {{ $invoice->invoice_status === 'Canceled' ? 'bg-rose-50 text-rose-700 border-rose-100'          : '' }}">
                        {{ $invoice->invoice_status }}
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Tenant</span>
                        <span class="font-semibold text-slate-700 mt-0.5 block">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Duration</span>
                        <span class="font-bold text-uds-blue mt-0.5 block">
                            {{ $invoice->duration_units ?? '-' }} {{ rtrim(ucfirst($invoice->unit->rentTypes ?? ''), 'ly') }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] block">Total Amount</span>
                        <span class="font-extrabold text-slate-800 mt-0.5 block">{{ number_format($totalAmount, 2) }} RWF</span>
                        @if ($paidAmount > 0 && $paidAmount < $totalAmount)
                            <span class="text-[10px] text-emerald-600 font-bold">Paid: {{ number_format($paidAmount, 2) }} RWF</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-2 border-t border-slate-50 flex-wrap">
                    <a href="{{ route('landlord.invoice.detail', $invoice->id) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                               text-uds-blue bg-blue-50 border-blue-200 hover:bg-blue-100">
                        <i class="fas fa-eye"></i> View
                    </a>

                    @if ($isPending)
                        <button wire:click="edit({{ $invoice->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                                   text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button wire:click="confirmDelete({{ $invoice->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                                   text-red-600 bg-red-50 border-red-200 hover:bg-red-100">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    @endif

                    <button wire:click="generatePdf({{ $invoice->id }})"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                               text-rose-700 bg-rose-50 border-rose-200 hover:bg-rose-100">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>

                    @if ($isPaid)
                        @if (!$invoice->ebmInvoice)
                            <button wire:click="addEbmInvoice({{ $invoice->id }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                                       text-violet-700 bg-violet-50 border-violet-200 hover:bg-violet-100">
                                <i class="fas fa-receipt"></i> EBM
                            </button>
                        @else
                            <button wire:click="viewEbmInvoice({{ $invoice->id }})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold rounded-xl border transition
                                       text-teal-700 bg-teal-50 border-teal-200 hover:bg-teal-100">
                                <i class="fas fa-eye"></i> EBM
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-slate-400">
                <i class="fas fa-file-invoice-dollar text-2xl mb-2 block text-slate-200"></i>
                <p class="font-semibold text-sm">No invoices found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $paginatedInvoices->links() }}
    </div>

    <!-- Include modals -->
    @include('livewire.user.land-lord.invoice.create-invoice-form')
    @include('livewire.user.land-lord.invoice.ebm-invoice-modal')
</div>
