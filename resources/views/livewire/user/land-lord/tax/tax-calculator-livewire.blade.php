<div class="p-6 bg-white rounded-lg shadow-lg">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Rental Income Tax Calculator</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Input Section -->
        <div class="space-y-4">
            <div class="p-4 rounded-lg bg-gray-50">
                <label for="annualIncome" class="block mb-2 text-sm font-medium text-gray-700">Annual Rental Income (FRW)</label>
                <input type="number"
                       wire:model="annualIncome"
                       id="annualIncome"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter annual rental income"
                       value="0">
            </div>

            <div class="p-4 rounded-lg bg-gray-50">
                <label for="bankInterest" class="block mb-2 text-sm font-medium text-gray-700">Bank Interest on Loan (FRW)</label>
                <input type="number"
                       wire:model="bankInterest"
                       id="bankInterest"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter bank interest">
            </div>

            <div class="p-4">
                <button wire:click="calculateTax" class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Calculate Tax
                </button>
            </div>
        </div>

        <!-- Results Section -->
        <div class="p-4 rounded-lg bg-gray-50">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Tax Breakdown</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Taxable Income (50%):</span>
                    <span class="font-medium">{{ number_format($taxableIncome ?? 0, 2) }} FRW</span>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">First Bracket (0%):</span>
                        <span>{{ number_format($taxBreakdown['first_bracket']['amount'] ?? 0, 2) }} FRW</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Middle Bracket (20%):</span>
                        <span>{{ number_format($taxBreakdown['middle_bracket']['amount'] ?? 0, 2) }} FRW</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Upper Bracket (30%):</span>
                        <span>{{ number_format($taxBreakdown['upper_bracket']['amount'] ?? 0, 2) }} FRW</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax at 0%:</span>
                        <span>{{ number_format($taxBreakdown['first_bracket']['tax'] ?? 0, 2) }} FRW</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax at 20%:</span>
                        <span>{{ number_format($taxBreakdown['middle_bracket']['tax'] ?? 0, 2) }} FRW</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax at 30%:</span>
                        <span>{{ number_format($taxBreakdown['upper_bracket']['tax'] ?? 0, 2) }} FRW</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between font-semibold">
                        <span class="text-gray-800">Total Tax:</span>
                        <span class="text-blue-600">{{ number_format($totalTax ?? 0, 2) }} FRW</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Rate Information -->
    <div class="p-4 mt-6 rounded-lg bg-blue-50">
        <h3 class="mb-2 text-lg font-semibold text-blue-800">Tax Rate Information</h3>
        <ul class="space-y-2 text-sm text-blue-700">
            <li>• 0% on first 180,000 FRW</li>
            <li>• 20% on income between 180,001 and 1,000,000 FRW</li>
            <li>• 30% on income above 1,000,000 FRW</li>
            <li>• Only 50% of rental income is taxable</li>
        </ul>
    </div>
</div>
