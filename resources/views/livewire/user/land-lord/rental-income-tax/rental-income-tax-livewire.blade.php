<div>
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <div class="flex justify-end mb-6">
            <button wire:click="exportToCsv"
                class="flex items-center px-4 py-2 text-white transition-colors bg-green-600 rounded-md hover:bg-green-700">
                <i class="mr-2 fas fa-file-csv"></i> Export CSV
                <span wire:loading wire:target="exportToCsv" class="ml-2">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </div>

        <!-- Tax Rate Info Box -->
        <div class="p-4 mb-6 border border-blue-200 rounded-lg bg-blue-50">
            <h3 class="flex items-center mb-2 font-medium text-blue-800">
                <i class="mr-2 fas fa-info-circle"></i> Rwanda Rental Income Tax Rates (Progressive)
            </h3>
            <ul class="ml-6 text-sm text-blue-700 list-disc">
                <li>0% on the first FRW 180,000 of annual taxable income</li>
                <li>20% on the portion between FRW 180,001 and FRW 1,000,000</li>
                <li>30% on the portion above FRW 1,000,000</li>
            </ul>
            <p class="mt-2 text-sm italic text-blue-600">Note: Only 50% of rental income is taxable. Tax is calculated progressively at the district level.</p>
        </div>

        <div class="overflow-x-auto">
            <div class="inline-block w-full align-middle">
                <!-- Summary Cards -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <!-- Total Rental Income Card -->
                    <div class="p-4 border border-indigo-200 rounded-lg bg-indigo-50">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-white bg-indigo-500 rounded-full">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-indigo-800">Total Rental Income (100%)</p>
                                <p class="text-2xl font-bold text-indigo-900">{{ number_format($grandTotalIncome, 2) }} FRW</p>
                            </div>
                        </div>
                    </div>

                    <!-- Taxable Income Card -->
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-white bg-blue-500 rounded-full">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Total Taxable Income (50%)</p>
                                <p class="text-2xl font-bold text-blue-900">{{ number_format($grandTotalIncome / 2, 2) }} FRW</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Tax Card -->
                    <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-white bg-green-500 rounded-full">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-800">Total Annual Tax</p>
                                <p class="text-2xl font-bold text-green-900">{{ number_format($grandTotalTax, 2) }} FRW</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reporting Period Card -->
                    <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                        <div class="flex items-center">
                            <div class="p-3 mr-4 text-white bg-purple-500 rounded-full">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-800">Reporting Period</p>
                                <p class="text-lg font-bold text-purple-900">Jan 1 - Dec 31, {{ now()->format('Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Districts Report -->
                @forelse($districtsWithInvoices as $district)
                <div class="mb-6 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-blue-200 bg-blue-50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h3 class="flex items-center text-lg font-medium text-blue-800">
                                <i class="mr-2 fas fa-map-marker-alt"></i>
                                {{ $district['district_name'] }}
                            </h3>
                            <div class="mt-2 text-right md:mt-0">
                                <p class="text-sm font-medium text-indigo-800">Rental Income (100%): {{ number_format($district['total_amount'], 2) }} FRW</p>
                                <p class="text-sm font-medium text-blue-800">Bank Interest: {{ number_format($district['bank_interest'], 2) }} FRW</p>
                                <p class="text-sm font-medium text-blue-800">Taxable Income (50% - Bank Interest): {{ number_format($district['taxable_amount'], 2) }} FRW</p>
                                <p class="text-sm font-medium text-green-800">Annual Tax: {{ number_format($district['total_tax'], 2) }} FRW</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progressive Tax Breakdown for this district -->
                    <div class="p-3 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="text-xs font-medium text-gray-700">
                                Tax Breakdown (Based on 50% Taxable Income):
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <div class="px-2 py-1 bg-blue-100 rounded-md">
                                    <span class="text-xs font-semibold text-blue-800">0%:</span>
                                    <span class="text-xs text-blue-700">{{ number_format($district['tax_breakdown']['first_bracket']['amount'], 2) }} FRW</span>
                                </div>
                                <div class="px-2 py-1 bg-blue-200 rounded-md">
                                    <span class="text-xs font-semibold text-blue-800">20%:</span>
                                    <span class="text-xs text-blue-700">{{ number_format($district['tax_breakdown']['middle_bracket']['amount'], 2) }} FRW ({{ number_format($district['tax_breakdown']['middle_bracket']['tax'], 2) }} FRW tax)</span>
                                </div>
                                <div class="px-2 py-1 bg-blue-300 rounded-md">
                                    <span class="text-xs font-semibold text-blue-800">30%:</span>
                                    <span class="text-xs text-blue-700">{{ number_format($district['tax_breakdown']['upper_bracket']['amount'], 2) }} FRW ({{ number_format($district['tax_breakdown']['upper_bracket']['tax'], 2) }} FRW tax)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Property
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    UPI
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    House
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Units
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Rental Income (100%)
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Taxable Income (50%)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($district['houses'] as $houseData)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ $houseData['property_name'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $houseData['property_upi'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="mr-2 text-gray-500 fas fa-home"></i>
                                        {{ $houseData['house']['name'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                        {{ $houseData['units_count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-indigo-900 whitespace-nowrap">
                                    {{ number_format($houseData['invoice_amount'], 2) }} FRW
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-blue-900 whitespace-nowrap">
                                    {{ number_format($houseData['invoice_amount'] / 2, 2) }} FRW
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-sm font-medium text-right text-gray-900">
                                    District Totals:
                                </td>
                                <td class="px-6 py-3 text-sm font-medium text-indigo-900">
                                    {{ number_format($district['total_amount'], 2) }} FRW
                                </td>
                                <td class="px-6 py-3 text-sm font-medium text-blue-900">
                                    {{ number_format($district['taxable_amount'], 2) }} FRW
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @empty
                <div class="p-4 mb-6 border-l-4 border-yellow-400 bg-yellow-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="text-yellow-400 fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No rental income data found for the current year.
                            </p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
