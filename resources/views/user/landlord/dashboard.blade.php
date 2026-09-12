<x-app-layout>
    <div class="mb-6 p-5 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-chart-pie text-white"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Portfolio Overview</p>
                    <h1 class="text-lg sm:text-xl font-extrabold text-uds-navy leading-tight">Welcome back, {{ $user->name }}</h1>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">{{ now()->format('l, d F Y') }} · Year-to-date {{ $year }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-extrabold rounded-xl text-white"
                      style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-calendar-alt text-[9px]"></i> {{ $year }} Summary
                </span>
                <a href="{{ route('landlord.crm-analytics') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl border border-slate-200 text-slate-600 hover:border-uds-blue hover:text-uds-blue transition">
                    CRM
                </a>
                <a href="{{ route('landlord.invoice') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold rounded-xl text-white"
                   style="background:#f39200;">
                    Invoices
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @php
            $kpis = [
                ['label' => 'Total Billed', 'value' => number_format($totalBilled).' RWF', 'sub' => 'Rent + VAT in '.$year, 'icon' => 'fa-file-invoice-dollar', 'color' => '#003b70', 'light' => 'rgba(0,59,112,0.08)'],
                ['label' => 'Collected', 'value' => number_format($collectedAmount).' RWF', 'sub' => $collectionRate.'% collection rate', 'icon' => 'fa-wallet', 'color' => '#2d9d3f', 'light' => 'rgba(45,157,63,0.10)'],
                ['label' => 'Outstanding', 'value' => number_format($outstandingAmount).' RWF', 'sub' => $pendingInvoices + $partialInvoices.' open invoices', 'icon' => 'fa-exclamation-circle', 'color' => '#dc2626', 'light' => 'rgba(220,38,38,0.08)'],
                ['label' => 'Occupancy', 'value' => $occupancyRate.'%', 'sub' => $occupiedUnits.' of '.$totalUnits.' units occupied', 'icon' => 'fa-key', 'color' => '#f39200', 'light' => 'rgba(243,146,0,0.10)'],
            ];
        @endphp
        @foreach($kpis as $kpi)
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $kpi['label'] }}</p>
                        <p class="text-lg sm:text-xl font-extrabold mt-1 leading-tight truncate" style="color:{{ $kpi['color'] }}">{{ $kpi['value'] }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 mt-1">{{ $kpi['sub'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $kpi['light'] }}">
                        <i class="fas {{ $kpi['icon'] }} text-sm" style="color:{{ $kpi['color'] }}"></i>
                    </div>
                </div>
                @if($kpi['label'] === 'Collected')
                    <div class="mt-3 w-full bg-slate-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full" style="width: {{ min($collectionRate, 100) }}%; background:#2d9d3f;"></div>
                    </div>
                @elseif($kpi['label'] === 'Occupancy')
                    <div class="mt-3 w-full bg-slate-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full" style="width: {{ min($occupancyRate, 100) }}%; background:#f39200;"></div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Portfolio</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Properties, buildings and units</p>
                </div>
                <a href="{{ route('landlord.property') }}" class="text-[10px] font-bold text-uds-orange hover:underline">Manage</a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    ['Properties', $totalProperties, '#003b70'],
                    ['Houses', $totalHouses, '#0b2545'],
                    ['Units', $totalUnits, '#2d9d3f'],
                    ['Vacant', $vacantUnits, '#f39200'],
                    ['Occupied', $occupiedUnits, '#059669'],
                    ['Maintenance', $maintenanceUnits, '#dc2626'],
                ] as [$lbl, $val, $col])
                    <div class="p-3 rounded-xl" style="background:#f8fafc;">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $lbl }}</p>
                        <p class="text-lg font-extrabold mt-0.5" style="color:{{ $col }}">{{ $val }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Clients & Contracts</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Active tenancy this period</p>
                </div>
                <a href="{{ route('landlord.tenant') }}" class="text-[10px] font-bold text-uds-orange hover:underline">Clients</a>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['Registered clients', $totalTenants],
                    ['Active tenants', $activeTenants],
                    ['Live contracts', $activeContracts],
                    ['Expiring in 60 days', $expiringContracts->count()],
                ] as [$lbl, $val])
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <span class="text-xs font-semibold text-slate-500">{{ $lbl }}</span>
                        <span class="text-sm font-extrabold text-uds-navy">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">{{ $year }} Financials</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Rent, VAT and collections</p>
                </div>
                <a href="{{ route('landlord.vat') }}" class="text-[10px] font-bold text-uds-orange hover:underline">VAT report</a>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['Rental amount', number_format($rentAmount).' RWF', '#003b70'],
                    ['VAT (18%)', number_format($vatAmount).' RWF', '#f39200'],
                    ['Total billed', number_format($totalBilled).' RWF', '#0b2545'],
                    ['Collected', number_format($collectedAmount).' RWF', '#2d9d3f'],
                    ['Outstanding', number_format($outstandingAmount).' RWF', '#dc2626'],
                ] as [$lbl, $val, $col])
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <span class="text-xs font-semibold text-slate-500">{{ $lbl }}</span>
                        <span class="text-xs font-extrabold" style="color:{{ $col }}">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Billing Trend</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Billed vs collected over the last 6 months</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="billingTrendChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Invoice Mix</h3>
                    <p class="text-[10px] font-semibold text-slate-400">{{ $totalInvoices }} invoices in {{ $year }}</p>
                </div>
            </div>
            <div class="h-40 mb-4">
                <canvas id="invoiceStatusChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['Paid', $paidInvoices, '#2d9d3f'],
                    ['Pending', $pendingInvoices, '#f39200'],
                    ['Partial', $partialInvoices, '#003b70'],
                    ['Canceled', $canceledInvoices, '#ef4444'],
                ] as [$lbl, $val, $col])
                    <div class="flex items-center justify-between px-2.5 py-2 rounded-xl" style="background:#f8fafc;">
                        <span class="text-[10px] font-bold text-slate-500">{{ $lbl }}</span>
                        <span class="text-xs font-extrabold" style="color:{{ $col }}">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Tax Summary {{ $year }}</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Estimated from current invoice and adjacement data</p>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('landlord.rental-income-tax') }}" class="block p-3.5 rounded-xl border border-slate-100 hover:border-uds-blue transition">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rental Income Tax</p>
                    <p class="text-lg font-extrabold text-uds-navy mt-1">{{ number_format($rentalTaxEstimate) }} <span class="text-xs text-slate-400">RWF</span></p>
                    <p class="text-[10px] font-semibold text-slate-400 mt-1">On {{ number_format($rentAmount) }} RWF rental income</p>
                </a>
                <a href="{{ route('landlord.vat') }}" class="block p-3.5 rounded-xl border border-slate-100 hover:border-uds-orange transition">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">VAT (18%)</p>
                    <p class="text-lg font-extrabold mt-1" style="color:#f39200;">{{ number_format($vatAmount) }} <span class="text-xs text-slate-400">RWF</span></p>
                    <p class="text-[10px] font-semibold text-slate-400 mt-1">Included in {{ number_format($totalBilled) }} RWF billed</p>
                </a>
                <a href="{{ route('landlord.property-tax') }}" class="block p-3.5 rounded-xl border border-slate-100 hover:border-uds-green transition">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Property Tax</p>
                    <p class="text-lg font-extrabold mt-1" style="color:#2d9d3f;">{{ number_format($propertyTax) }} <span class="text-xs text-slate-400">RWF</span></p>
                    <p class="text-[10px] font-semibold text-slate-400 mt-1">Land + building adjacement for {{ $year }}</p>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">EBM Compliance</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Paid invoices with EBM attachment</p>
                </div>
            </div>
            <div class="h-40 mb-3">
                <canvas id="ebmInvoiceChart"></canvas>
            </div>
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-emerald-600">Compliant {{ $paidWithEbm }}</span>
                <span class="text-slate-400">Missing {{ $paidWithoutEbm }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Contracts Expiring</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Next 60 days</p>
                </div>
                <a href="{{ route('landlord.RentRecord') }}" class="text-[10px] font-bold text-uds-orange hover:underline">Records</a>
            </div>
            <div class="space-y-2.5">
                @forelse($expiringContracts as $record)
                    <div class="flex items-center justify-between p-3 rounded-xl" style="background:#f8fafc;">
                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-uds-navy truncate">{{ $record->tenant->tenant_name ?? 'Tenant' }}</p>
                            <p class="text-[10px] font-semibold text-slate-400 truncate">{{ optional($record->unit->house)->name ?? 'Unit' }}</p>
                        </div>
                        <span class="text-[10px] font-extrabold text-uds-orange shrink-0">{{ \Carbon\Carbon::parse($record->end_date)->format('d M Y') }}</span>
                    </div>
                @empty
                    <p class="text-xs font-semibold text-slate-400 py-8 text-center">No contracts expiring in the next 60 days.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-8">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-uds-navy">Recent Invoices</h3>
                    <p class="text-[10px] font-semibold text-slate-400">Latest billing statements</p>
                </div>
                <a href="{{ route('landlord.invoice') }}" class="text-[10px] font-bold text-uds-orange hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr style="background:#f8fafc;">
                            @foreach(['Invoice','Client','Amount','VAT','Total','Status'] as $th)
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $th }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $invoice)
                            <tr class="border-t border-slate-50">
                                <td class="py-3.5 px-4 text-xs font-extrabold text-uds-navy">{{ $invoice->invoice_no }}</td>
                                <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">{{ $invoice->tenant->tenant_name ?? 'N/A' }}</td>
                                <td class="py-3.5 px-4 text-xs font-bold text-slate-700">{{ number_format($invoice->amount) }}</td>
                                <td class="py-3.5 px-4 text-xs font-bold" style="color:#f39200;">{{ number_format($invoice->vat) }}</td>
                                <td class="py-3.5 px-4 text-xs font-extrabold text-uds-navy">{{ number_format($invoice->amount + $invoice->vat) }}</td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $statusStyles = [
                                            'Paid' => 'background:#ecfdf5;color:#047857;',
                                            'Pending' => 'background:#fff7ed;color:#c2410c;',
                                            'Partial' => 'background:#eff6ff;color:#1d4ed8;',
                                            'Canceled' => 'background:#fef2f2;color:#b91c1c;',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-extrabold rounded-lg"
                                          style="{{ $statusStyles[$invoice->invoice_status] ?? 'background:#f8fafc;color:#64748b;' }}">
                                        {{ $invoice->invoice_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-xs font-semibold text-slate-400">No invoices yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <h3 class="text-sm font-extrabold text-uds-navy mb-1">Quick Actions</h3>
            <p class="text-[10px] font-semibold text-slate-400 mb-4">Jump to the most used modules</p>
            <div class="space-y-2">
                @foreach([
                    ['landlord.invoice', 'fa-file-invoice-dollar', 'Create / view invoices', '#003b70'],
                    ['landlord.RentRecord', 'fa-file-signature', 'Rent records', '#0b2545'],
                    ['landlord.tenant', 'fa-user-friends', 'Client directory', '#2d9d3f'],
                    ['landlord.rental-income-tax', 'fa-percent', 'Rental income tax', '#f39200'],
                    ['landlord.vat', 'fa-receipt', 'VAT report', '#ea580c'],
                    ['landlord.property-tax', 'fa-hand-holding-usd', 'Property tax', '#059669'],
                    ['landlord.crm-analytics', 'fa-chart-line', 'CRM analytics', '#7c3aed'],
                    ['landlord.paymentmode', 'fa-credit-card', 'Payment modes', '#0369a1'],
                ] as [$route, $icon, $label, $color])
                    <a href="{{ route($route) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl border border-slate-100 hover:border-slate-200 transition">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:{{ $color }}14;">
                            <i class="fas {{ $icon }} text-xs" style="color:{{ $color }}"></i>
                        </span>
                        <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const font = { family: 'Outfit', size: 11 };
            const tooltip = {
                backgroundColor: '#0b2545',
                titleFont: { family: 'Outfit', size: 11, weight: 'bold' },
                bodyFont: { family: 'Outfit', size: 11 },
                padding: 8
            };

            new Chart(document.getElementById('billingTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($monthlyTrend['labels']),
                    datasets: [
                        {
                            label: 'Billed',
                            data: @json($monthlyTrend['billed']),
                            borderColor: '#003b70',
                            backgroundColor: 'rgba(0,59,112,0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 3
                        },
                        {
                            label: 'Collected',
                            data: @json($monthlyTrend['collected']),
                            borderColor: '#2d9d3f',
                            backgroundColor: 'rgba(45,157,63,0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { font, boxWidth: 10 } }, tooltip },
                    scales: {
                        x: { ticks: { font }, grid: { display: false } },
                        y: { ticks: { font, callback: v => Number(v).toLocaleString() }, grid: { color: '#f1f5f9' } }
                    }
                }
            });

            new Chart(document.getElementById('invoiceStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending', 'Partial', 'Canceled'],
                    datasets: [{
                        data: [{{ $paidInvoices }}, {{ $pendingInvoices }}, {{ $partialInvoices }}, {{ $canceledInvoices }}],
                        backgroundColor: ['#2d9d3f', '#f39200', '#003b70', '#ef4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false }, tooltip }
                }
            });

            new Chart(document.getElementById('ebmInvoiceChart'), {
                type: 'doughnut',
                data: {
                    labels: ['EBM Compliant', 'Missing EBM'],
                    datasets: [{
                        data: [{{ $paidWithEbm }}, {{ $paidWithoutEbm }}],
                        backgroundColor: ['#2d9d3f', '#e2e8f0'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false }, tooltip }
                }
            });
        });
    </script>
</x-app-layout>
