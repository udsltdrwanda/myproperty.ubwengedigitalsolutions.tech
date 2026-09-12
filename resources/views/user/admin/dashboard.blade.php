<x-app-layout>
    <div class="mb-6 p-5 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                     style="background:linear-gradient(135deg,#003b70,#0b2545);">
                    <i class="fas fa-shield-alt text-white"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Administration</p>
                    <h1 class="text-lg font-extrabold text-uds-navy">Welcome, {{ Auth::user()->name }}</h1>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">Platform summary · {{ now()->format('d F Y') }}</p>
                </div>
            </div>
            <a href="{{ route('admin.user.request') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-xl"
               style="background:#f39200;">
                Review requests
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['Users', $totalUsers, 'fa-users', '#003b70'],
            ['Landlord accounts', $landlords, 'fa-user-tie', '#0b2545'],
            ['Pending requests', $pendingRequests, 'fa-inbox', '#f39200'],
            ['Approved requests', $approvedRequests, 'fa-check-circle', '#2d9d3f'],
            ['Properties', $totalProperties, 'fa-building', '#0369a1'],
            ['Clients', $totalTenants, 'fa-user-friends', '#7c3aed'],
            ['Invoices', $totalInvoices, 'fa-file-invoice-dollar', '#ea580c'],
            ['Today', now()->format('d M'), 'fa-calendar-day', '#059669'],
        ] as [$label, $value, $icon, $color])
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                        <p class="text-xl font-extrabold mt-1" style="color:{{ $color }}">{{ $value }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $color }}14;">
                        <i class="fas {{ $icon }}" style="color:{{ $color }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-uds-navy">Recent Access Requests</h3>
                <p class="text-[10px] font-semibold text-slate-400">Latest landlord applications</p>
            </div>
            <a href="{{ route('admin.user.request') }}" class="text-[10px] font-bold text-uds-orange hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr style="background:#f8fafc;">
                        @foreach(['Name','Email','Phone','Status','Date'] as $th)
                            <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests as $request)
                        <tr class="border-t border-slate-50">
                            <td class="py-3.5 px-4 text-xs font-extrabold text-uds-navy">{{ $request->name }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">{{ $request->email }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">{{ $request->phone }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-lg"
                                      style="{{ $request->status === 'approved' ? 'background:#ecfdf5;color:#047857;' : ($request->status === 'pending' ? 'background:#fff7ed;color:#c2410c;' : 'background:#fef2f2;color:#b91c1c;') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-400">{{ optional($request->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-xs font-semibold text-slate-400">No requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
