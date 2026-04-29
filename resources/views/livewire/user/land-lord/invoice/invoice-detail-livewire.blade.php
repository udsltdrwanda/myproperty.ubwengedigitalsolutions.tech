<div class="p-4 bg-white rounded shadow">
    @if ($invoice)
        <!-- Header Section -->
        <div class="flex flex-row justify-between pb-6 border-b border-blue-500 md:flex-row">
            <!-- Company Info -->
            <div class="w-full md:w-3/5">
                <h2 class="mb-1 text-xl font-bold text-blue-500">{{ $landlord->name ?? 'Your Company Name' }}</h2>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->name ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->country ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->provinceRelation->name ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->districtRelation->name ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->sectorRelation->name ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->cellRelation->name ?? '' }}</p>
                <p class="my-0 text-sm text-gray-600">{{ $invoice->property->VillageRelation->name ?? '' }}</p>
            </div>

            <!-- Invoice Info -->
            <div class="w-full mt-4 text-left md:w-2/5 md:mt-0 md:text-right">
                <h1 class="mb-2 text-2xl font-bold text-blue-500">INVOICE</h1>
                <p class="my-1 text-sm"><span class="font-semibold">Invoice #:</span> {{ $invoice->invoice_no }}</p>
                <p class="my-1 text-sm"><span class="font-semibold">Date:</span>
                    {{ $invoice->created_at->format('F j, Y') }}</p>
                <p class="my-1 text-sm"><span class="font-semibold">Due Date:</span>
                    {{ $invoice->due_date->format('F j, Y') }}</p>
                @php
                    $status = ucfirst($invoice->invoice_status);
                    $statusClasses = match (strtolower($invoice->invoice_status)) {
                        'paid' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'canceled' => 'bg-red-100 text-red-800',
                        'partial' => 'bg-blue-100 text-blue-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <div class="{{ $statusClasses }} py-2">
                    <p class="my-1 text-sm">
                        <span class="font-semibold">Status:</span>
                        <span class="px-2 py-1 text-xs font-medium rounded ">
                            {{ $status }}
                        </span>
                    </p>
                </div>

                @if (!in_array($invoice->invoice_status, ['Paid', 'Canceled']))
                    <div class="mt-4">
                        <x-button2 color="green" action="showPaymentForm" icon="fas fa-credit-card">
                            Record Payment
                        </x-button2>
                    </div>
                @endif

                {{-- Payment Modal --}}
                @if ($showPaymentModal)
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
                        <div class="relative w-full max-w-2xl mx-auto my-6">
                            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                                <h2 class="mb-4 text-lg font-semibold">Make a Payment</h2>
                                <div class="mb-4 text-sm text-gray-700">
                                    <p><strong>Invoice Amount:</strong>
                                        {{ number_format($invoice->amount, 2) }}</p>
                                    <p><strong>VAT:</strong>
                                        {{ number_format($invoice->vat ?? 0, 2) }}</p>
                                    <p><strong>Total Due:</strong>
                                        {{ number_format($totalDue, 2) }}
                                    </p>
                                    <p><strong>Total Paid:</strong>
                                        {{ number_format($totalPaid, 2) }}
                                    </p>
                                    <p><strong>Remaining Balance:</strong>
                                        {{ number_format(max(0, $totalDue - $totalPaid), 2) }}
                                    </p>
                                </div>
                                <form enctype="multipart/form-data" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium">Amount</label>
                                        <x-input type="number" step="0.01" wire:model="payed_amount"
                                            class="w-full mt-1 input" />
                                        @error('payed_amount')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Payment Mode</label>
                                        <select wire:model.defer="payment_mode" class="w-full p-2 border rounded">
                                            <option value="">-- Select Payment Mode --</option>
                                            @foreach ($payment_modes as $payment)
                                                <option value="{{ $payment->id }}">{{ $payment->account_number }},
                                                    {{ ucfirst($payment->account_name) }}</option>
                                            @endforeach
                                        </select>

                                        @error('payment_mode')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Payment Date</label>
                                        <x-input type="date" wire:model.defer="payment_date"
                                            class="w-full mt-1 input" />
                                        @error('payment_date')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Payment Reason</label>
                                        <textarea wire:model.defer="payment_reason"
                                            class="w-full p-3 pl-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                        @error('payment_reason')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium">Payment Proof (optional)</label>
                                        <input type="file" wire:model="payment_proof" class="w-full mt-1 input" />
                                        @error('payment_proof')
                                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" wire:click="$set('showPaymentModal', false)"
                                            class="px-3 py-2 text-sm text-gray-600 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                                        <x-button2 type="submit" color="blue" action="makePayment">
                                            Submit
                                        </x-button2>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Client and Unit Info -->
        <div class="flex flex-row justify-between mt-6 mb-3 border border-gray-200 md:flex-row">
            <!-- Bill To -->
            <div class="w-full mb-4 md:w-1/2 md:mb-0">
                <div class="px-4 py-2 mb-2 font-semibold border-l-4 border-blue-500 bg-gray-50">
                    BILL TO
                </div>
                <div class="px-4">
                    <p class="my-1 text-sm font-semibold">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</p>
                    <p class="my-1 text-sm text-gray-600">{{ $invoice->tenant->company_name ?? '' }}</p>
                    <p class="my-1 text-sm text-gray-600">{{ $invoice->tenant->phone ?? '' }}</p>
                    <p class="my-1 text-sm text-gray-600">{{ $invoice->tenant->email ?? '' }}</p>
                    <p class="my-1 text-sm text-gray-600">
                        {{ $invoice->tenant->company_tin ? 'TIN: ' . $invoice->tenant->company_tin : '' }}
                    </p>
                </div>
            </div>

            <!-- Unit Details -->
            <div class="w-full md:w-1/2 md:pl-4">
                <div class="px-4 py-2 mb-2 font-semibold border-l-4 border-blue-500 bg-gray-50">
                    UNIT DETAILS
                </div>
                <div class="px-4">
                    <p class="my-1 text-sm">Unit: {{ $invoice->unit->name ?? 'N/A' }}</p>
                    <p class="my-1 text-sm">Room: {{ $invoice->unit->roomNumber ?? '' }}</p>
                    <p class="my-1 text-sm">Rent Type: {{ ucfirst($invoice->unit->rentTypes ?? '') }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="border border-gray-200">
            <div>
                <div class="px-4 py-2 mb-4 font-semibold border-l-4 border-blue-500 bg-gray-50">
                    INVOICE DETAILS
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left">Description</th>
                                <th class="px-4 py-2 text-right">Amount (RWF)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3">
                                   Invoice On  Rent Contact for {{ \Carbon\Carbon::parse($invoice->start_date)->format('m-d-Y') }}
                                    To
                                    {{ \Carbon\Carbon::parse($invoice->end_date)->format('m-d-Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    {{ number_format($invoice->amount, 2) }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3">VAT</td>
                                <td class="px-4 py-3 text-right">
                                    {{ number_format($invoice->vat, 2) }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3">Duration Time</td>
                                <td class="px-4 py-3 text-right">{{ $invoice->duration_units }}
                                    &nbsp;{{ ucfirst(substr($invoice->unit->rentTypes ?? '', 0, -2)) }}(s)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Summary -->
            @php
                $totalAmount = $invoice->amount + $invoice->vat;
                $paidAmount = $invoice->payments->sum('payed_amount') ?? 0;
                $balanceDue = $totalAmount - $paidAmount;
            @endphp

            <div class="flex justify-end mt-6">
                <table class="w-full md:w-1/2">
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">Total:</td>
                        <td class="px-4 py-2 text-right">{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="px-4 py-2">Amount Paid:</td>
                        <td class="px-4 py-2 text-right">{{ number_format($paidAmount, 2) }}</td>
                    </tr>
                    <tr class="font-semibold border-t-2 border-blue-500">
                        <td class="px-4 py-2">Balance Due:</td>
                        <td class="px-4 py-2 text-right">{{ number_format($balanceDue, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="my-4">
            @if ($invoice->payments->isEmpty())
            @else
                <hr>
                <h3 class="mb-2 text-lg font-bold">Payment Details</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Amount Paid</th>
                                <th class="px-4 py-2 border-b">Payment Method</th>
                                <th class="px-4 py-2 border-b">Paid At</th>
                                <th class="px-4 py-2 border-b">Proof</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->payments as $index => $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ number_format($payment->payed_amount, 2) }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ ucfirst($payment->paymentMode->account_name ?? 'N/A') }}</td>
                                    <td class="px-4 py-2 border-b">{{ $payment->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        <a href="{{ $payment->payment_proof }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            View Document
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="pt-4 mt-10 text-sm text-center text-gray-500 border-t border-gray-200">
            <p class="mt-1">Invoice generated on: {{ now()->format('F j, Y \a\t H:i') }}</p>
        </div>
    @else
        <p class="text-gray-500">No invoice selected.</p>
    @endif
</div>
