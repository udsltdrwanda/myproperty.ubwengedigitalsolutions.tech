<x-app-layout>
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="mr-2 text-blue-600 fas fa-tachometer-alt"></i> Welcome Back, {{ Auth::user()->name }}
                </h3>
                <p class="mt-1 text-gray-600">{{ Auth::user()->userRole }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Today's Date</p>
                <p class="text-lg font-semibold text-gray-800">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    @php
        // Get total properties
        $totalProperties = \App\Models\Property::where('landlord_id', Auth::user()->landlord_id)->count();

        // Get total houses
        $totalHouses = \App\Models\House::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->count();

        // Get property units statistics
        $totalUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->count();

        $vacantUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->whereDoesntHave('activeRentRecord')->count();

        $occupiedUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->whereHas('activeRentRecord')->count();

        $maintenanceUnits = \App\Models\PropertyUnit::whereHas('house.property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->where('unit_status', 'maintenance')->count();

        // Get invoice statistics
        $totalInvoices = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->count();

        $pendingInvoices = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->where('invoice_status', 'Pending')->count();

        $paidInvoices = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->where('invoice_status', 'Paid')->count();

        $partialInvoices = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->where('invoice_status', 'Partial')->count();

        $canceledInvoices = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->where('invoice_status', 'Canceled')->count();

        // Get paid invoices with EBM invoices
        $paidInvoicesWithEbm = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })
        ->where('invoice_status', 'Paid')
        ->whereHas('ebmInvoice')
        ->count();

        $paidInvoicesWithoutEbm = \App\Models\Invoice::whereHas('property', function($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })
        ->where('invoice_status', 'Paid')
        ->whereDoesntHave('ebmInvoice')
        ->count();
    @endphp

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Property Statistics -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Properties</p>
                        <h3 class="mt-2 text-3xl font-bold text-blue-600">{{ $totalProperties }}</h3>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="text-2xl text-blue-600 fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Houses Statistics -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Houses</p>
                        <h3 class="mt-2 text-3xl font-bold text-purple-600">{{ $totalHouses }}</h3>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="text-2xl text-purple-600 fas fa-home"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Units Statistics -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Property Units</p>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="text-2xl text-green-600 fas fa-door-open"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Units</span>
                        <span class="font-semibold text-gray-800">{{ $totalUnits }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Vacant</span>
                        <span class="font-semibold text-yellow-600">{{ $vacantUnits }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Occupied</span>
                        <span class="font-semibold text-green-600">{{ $occupiedUnits }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Maintenance</span>
                        <span class="font-semibold text-red-600">{{ $maintenanceUnits }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Statistics -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Invoices</p>
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="text-2xl text-indigo-600 fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Invoices</span>
                        <span class="font-semibold text-gray-800">{{ $totalInvoices }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Pending</span>
                        <span class="font-semibold text-yellow-600">{{ $pendingInvoices }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Paid</span>
                        <span class="font-semibold text-green-600">{{ $paidInvoices }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Status Chart -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md lg:col-span-2">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Invoice Status Distribution</p>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="text-2xl text-blue-600 fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="invoiceStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Paid Invoices with EBM Chart -->
        <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-sm rounded-xl hover:shadow-md lg:col-span-2">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-gray-600">Paid Invoices with EBM</p>
                    <div class="p-3 bg-teal-100 rounded-full">
                        <i class="text-2xl text-teal-600 fas fa-file-invoice"></i>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="ebmInvoiceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Invoice Status Chart
            const ctx = document.getElementById('invoiceStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending', 'Partial', 'Canceled'],
                    datasets: [{
                        data: [{{ $paidInvoices }}, {{ $pendingInvoices }}, {{ $partialInvoices }}, {{ $canceledInvoices }}],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',  // Green for Paid
                            'rgba(234, 179, 8, 0.8)',   // Yellow for Pending
                            'rgba(59, 130, 246, 0.8)',  // Blue for Partial
                            'rgba(239, 68, 68, 0.8)'    // Red for Canceled
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(234, 179, 8)',
                            'rgb(59, 130, 246)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // EBM Invoice Chart
            const ebmCtx = document.getElementById('ebmInvoiceChart').getContext('2d');
            new Chart(ebmCtx, {
                type: 'pie',
                data: {
                    labels: ['With EBM Invoice', 'Without EBM Invoice'],
                    datasets: [{
                        data: [{{ $paidInvoicesWithEbm }}, {{ $paidInvoicesWithoutEbm }}],
                        backgroundColor: [
                            'rgba(20, 184, 166, 0.8)',  // Teal for With EBM
                            'rgba(156, 163, 175, 0.8)'   // Gray for Without EBM
                        ],
                        borderColor: [
                            'rgb(20, 184, 166)',
                            'rgb(156, 163, 175)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
