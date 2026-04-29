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
                        {{ number_format(($invoice->vat ?? 0), 2) }}</p>
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
                {{-- @include('livewire.user.land-lord.invoice.payment-model') --}}
                <form wire:submit.prevent="makePayment"  enctype="multipart/form-data" class="space-y-4">
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
                        <x-input type="date" wire:model.defer="payment_date" class="w-full mt-1 input" />
                        @error('payment_date')
                            <span class="text-sm text-red-500">{{ $message ?? '' }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Payment Reason</label>
                        <textarea wire:model.defer="payment_reason" class="w-full p-3 pl-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
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
                        <x-button2 type="submit" color="blue">
                            Submit
                        </x-button2>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
