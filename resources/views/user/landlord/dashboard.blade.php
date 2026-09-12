<x-app-layout>
    @php
        $landlordId = Auth::user()->landlord_id;

        // Get total properties
        $totalProperties = \App\Models\Property::where('landlord_id', $landlordId)->count();

        // Get total houses
        $totalHouses = \App\Models\House::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->count();

        // Get property units statistics
        $totalUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->count();

        $vacantUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->whereDoesntHave('activeRentRecord')->count();

        $occupiedUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->whereHas('activeRentRecord')->count();

        $maintenanceUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('unit_status', 'maintenance')->count();

        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;

        // Get total clients/tenants
        $totalTenants = \App\Models\Tenant::where('landlord_id', $landlordId)->count();

        // Get invoice statistics
        $totalInvoices = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->count();

        $pendingInvoices = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', 'Pending')->count();

        $paidInvoices = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', 'Paid')->count();

        $partialInvoices = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', 'Partial')->count();

        $canceledInvoices = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', 'Canceled')->count();

        // Calculate Revenue statistics
        $totalInvoicedAmount = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', '!=', 'Canceled')->sum('amount');

        $collectedAmount = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->where('invoice_status', 'Paid')->sum('amount');

        $outstandingAmount = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->whereIn('invoice_status', ['Pending', 'Partial'])->sum('amount');

        // Get paid invoices with EBM invoices
        $paidInvoicesWithEbm = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })
        ->where('invoice_status', 'Paid')
        ->whereHas('ebmInvoice')
        ->count();

        $paidInvoicesWithoutEbm = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })
        ->where('invoice_status', 'Paid')
        ->whereDoesntHave('ebmInvoice')
        ->count();

        // Fetch recent invoices (last 5)
        $recentInvoicesList = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })->with(['property', 'tenant'])->latest()->limit(5)->get();

        // Get monthly billing metrics (past 6 months)
        $monthlyBilling = \App\Models\Invoice::whereHas('property', function($query) use ($landlordId) {
            $query->where('landlord_id', $landlordId);
        })
        ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, SUM(amount) as total, MIN(created_at) as sort_date")
        ->groupBy('month')
        ->orderBy('sort_date')
        ->limit(6)
        ->get();

        $monthlyMonths = $monthlyBilling->pluck('month')->toArray();
        $monthlyTotals = $monthlyBilling->pluck('total')->toArray();

        if (empty($monthlyMonths)) {
            $monthlyMonths = [now()->format('b Y')];
            $monthlyTotals = [0];
        }
    @endphp



    <!-- Core performance metrics KPI Row -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
        
        <!-- Total Rent Collected -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:border-uds-blue transition-colors duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-semibold text-slate-400">Total Rent Collected</p>
                    <h3 class="mt-2 text-xl sm:text-2xl font-extrabold text-uds-navy">{{ number_format($collectedAmount) }} <span class="text-xs font-bold text-slate-500">RWF</span></h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl text-uds-blue">
                    <i class="text-xl fas fa-wallet"></i>
                </div>
            </div>
            @php
                $collectionProgress = $totalInvoicedAmount > 0 ? round(($collectedAmount / $totalInvoicedAmount) * 100, 1) : 0;
            @endphp
            <div class="mt-4">
                <div class="flex justify-between text-[11px] font-bold text-slate-500 mb-1">
                    <span>Collection Rate</span>
                    <span>{{ $collectionProgress }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-uds-blue h-1.5 rounded-full" style="width: {{ $collectionProgress }}%"></div>
                </div>
            </div>
            <div class="mt-3 text-[11px] text-slate-400 font-semibold flex justify-between border-t border-slate-50 pt-2.5">
                <span>Total Invoiced:</span>
                <span class="text-slate-700 font-bold">{{ number_format($totalInvoicedAmount) }} RWF</span>
            </div>
        </div>

        <!-- Occupancy & Tenant Count -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:border-uds-orange transition-colors duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-semibold text-slate-400">Occupancy & Tenants</p>
                    <h3 class="mt-2 text-xl sm:text-2xl font-extrabold text-uds-navy">{{ $occupancyRate }}%</h3>
                </div>
                <div class="p-3 bg-orange-50 rounded-xl text-uds-orange">
                    <i class="text-xl fas fa-key"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-uds-orange h-1.5 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                </div>
            </div>
            <div class="space-y-1.5 mt-3 text-xs font-semibold text-slate-500 border-t border-slate-50 pt-2.5 flex justify-between items-center">
                <span>Active Tenants:</span>
                <span class="text-slate-800 font-extrabold">{{ $totalTenants }} Registered</span>
            </div>
        </div>

        <!-- Asset Metrics -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:border-uds-green transition-colors duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-semibold text-slate-400">Properties & Units</p>
                    <h3 class="mt-2 text-xl sm:text-2xl font-extrabold text-uds-navy">{{ $totalProperties }} <span class="text-xs font-bold text-slate-500">Listed</span></h3>
                </div>
                <div class="p-3 bg-green-50 rounded-xl text-uds-green">
                    <i class="text-xl fas fa-building"></i>
                </div>
            </div>
            <div class="space-y-2 mt-4 pt-2.5 border-t border-slate-100 text-xs font-semibold text-slate-500">
                <div class="flex justify-between">
                    <span>Total Houses:</span>
                    <span class="text-slate-800 font-bold">{{ $totalHouses }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Units:</span>
                    <span class="text-slate-800 font-bold">{{ $totalUnits }} ({{ $vacantUnits }} vacant)</span>
                </div>
            </div>
        </div>

        <!-- Pending Collections -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:border-red-500 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-semibold text-slate-400">Pending Collections</p>
                    <h3 class="mt-2 text-xl sm:text-2xl font-extrabold text-red-500">{{ number_format($outstandingAmount) }} <span class="text-xs font-bold text-slate-500">RWF</span></h3>
                </div>
                <div class="p-3 bg-red-50 rounded-xl text-red-500">
                    <i class="text-xl fas fa-file-invoice-dollar"></i>
                </div>
            </div>
            <div class="space-y-2 mt-4 pt-2.5 border-t border-slate-100 text-xs font-semibold text-slate-500">
                <div class="flex justify-between">
                    <span>Unpaid Invoices:</span>
                    <span class="text-red-500 font-bold">{{ $pendingInvoices }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Paid Invoices:</span>
                    <span class="text-emerald-500 font-bold">{{ $paidInvoices }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Analytics Charts Row -->
    <div class="mb-8">
        <!-- Compliance & Invoice Status Charts (Combined Container) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-base font-bold text-uds-navy">Invoices & Tax Compliance</h4>
                    <p class="text-xs text-slate-400 font-medium">Compliance stats and invoice distribution</p>
                </div>
                <div class="p-2.5 bg-teal-50 rounded-xl text-teal-600">
                    <i class="text-lg fas fa-chart-pie"></i>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-64">
                <div class="relative h-full flex flex-col justify-center items-center">
                    <p class="text-xs font-bold text-slate-400 mb-2">Invoice Status</p>
                    <div class="w-full h-48 relative">
                        <canvas id="invoiceStatusChart"></canvas>
                    </div>
                </div>
                <div class="relative h-full flex flex-col justify-center items-center">
                    <p class="text-xs font-bold text-slate-400 mb-2">EBM Compliance</p>
                    <div class="w-full h-48 relative">
                        <canvas id="ebmInvoiceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Hub & Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Services Control Panel -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div>
                    <h4 class="text-base font-bold text-uds-navy">Services Control Hub</h4>
                    <p class="text-xs text-slate-400 font-medium">Quick links to active modules and settings</p>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl text-slate-500">
                    <i class="text-lg fas fa-th-large"></i>
                </div>
            </div>
            
            <div class="space-y-4">
                <!-- Managers -->
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-slate-50 border border-slate-100 rounded-xl transition duration-150">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2.5 bg-blue-50 rounded-lg text-uds-blue text-sm">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-uds-navy">Assistant Managers</p>
                            <p class="text-[10px] text-slate-400 font-medium">Configure roles and dashboard access rights</p>
                        </div>
                    </div>
                    <a href="{{ route('landlord.property.manager') }}" class="px-3 py-1.5 bg-uds-blue hover:bg-[#002f5a] text-white text-[11px] font-bold rounded-lg transition">Manage</a>
                </div>

                <!-- Taxation Center -->
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-slate-50 border border-slate-100 rounded-xl transition duration-150">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2.5 bg-green-50 rounded-lg text-uds-green text-sm">
                            <i class="fas fa-university"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-uds-navy">Taxation Center</p>
                            <p class="text-[10px] text-slate-400 font-medium">Rental Income Tax, VAT & Property Tax reporting</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('landlord.rental-income-tax') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">Income Tax</a>
                        <a href="{{ route('landlord.vat') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">VAT</a>
                        <a href="{{ route('landlord.property-tax') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">Property Tax</a>
                    </div>
                </div>

                <!-- Payment Modes -->
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-slate-50 border border-slate-100 rounded-xl transition duration-150">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2.5 bg-orange-50 rounded-lg text-uds-orange text-sm">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-uds-navy">Payment Settings</p>
                            <p class="text-[10px] text-slate-400 font-medium">Add payment gateways, banking info, and MoMo codes</p>
                        </div>
                    </div>
                    <a href="{{ route('landlord.paymentmode') }}" class="px-3 py-1.5 bg-uds-orange hover:bg-orange-600 text-white text-[11px] font-bold rounded-lg transition">Configure</a>
                </div>

                <!-- Petty Cash -->
                <div class="flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-slate-50 border border-slate-100 rounded-xl transition duration-150">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2.5 bg-rose-50 rounded-lg text-rose-500 text-sm">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-uds-navy">Petty Cash Ledgers</p>
                            <p class="text-[10px] text-slate-400 font-medium">Log direct expenses, office costs, and internal flow</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 bg-slate-100 text-slate-400 text-[11px] font-bold rounded-lg select-none">No records</span>
                </div>
            </div>
        </div>

        <!-- Recent Invoices Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="text-base font-bold text-uds-navy">Recent Invoices</h4>
                    <p class="text-xs text-slate-400 font-medium">Overview of the latest billing statements</p>
                </div>
                <a href="{{ route('landlord.invoice') }}" class="text-xs font-bold text-uds-orange hover:underline">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100">
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Invoice No</th>
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tenant</th>
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Amount</th>
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentInvoicesList as $invoice)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-3.5 px-4 text-xs font-bold text-uds-navy">{{ $invoice->invoice_no }}</td>
                                <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                                    {{ $invoice->tenant->tenant_name ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-extrabold text-uds-navy text-right">{{ number_format($invoice->amount) }} RWF</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($invoice->invoice_status === 'Paid')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-50 text-emerald-700 border border-emerald-200/50">Paid</span>
                                    @elseif($invoice->invoice_status === 'Pending')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded bg-amber-50 text-amber-700 border border-amber-200/50">Pending</span>
                                    @elseif($invoice->invoice_status === 'Partial')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded bg-blue-50 text-blue-700 border border-blue-200/50">Partial</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold rounded bg-red-50 text-red-700 border border-red-200/50">{{ $invoice->invoice_status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-4 text-center text-xs font-semibold text-slate-400 bg-slate-50/10">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart JS Integrations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Invoice Status Distribution Chart
            const statusCtx = document.getElementById('invoiceStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending', 'Partial', 'Canceled'],
                    datasets: [{
                        data: [{{ $paidInvoices }}, {{ $pendingInvoices }}, {{ $partialInvoices }}, {{ $canceledInvoices }}],
                        backgroundColor: [
                            '#2d9d3f',  // Green (UDS Green)
                            '#f39200',  // Orange (UDS Orange)
                            '#003b70',  // Blue (UDS Blue)
                            '#ef4444'   // Red
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0b2545',
                            titleFont: { family: 'Outfit', size: 11, weight: 'bold' },
                            bodyFont: { family: 'Outfit', size: 11 },
                            padding: 8,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return ` ${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // 3. EBM Invoice Chart
            const ebmCtx = document.getElementById('ebmInvoiceChart').getContext('2d');
            new Chart(ebmCtx, {
                type: 'pie',
                data: {
                    labels: ['EBM Compliant', 'Non-Compliant'],
                    datasets: [{
                        data: [{{ $paidInvoicesWithEbm }}, {{ $paidInvoicesWithoutEbm }}],
                        backgroundColor: [
                            '#2d9d3f',  // Green (UDS Green)
                            '#e2e8f0'   // Gray (slate-200)
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0b2545',
                            titleFont: { family: 'Outfit', size: 11, weight: 'bold' },
                            bodyFont: { family: 'Outfit', size: 11 },
                            padding: 8,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return ` ${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
