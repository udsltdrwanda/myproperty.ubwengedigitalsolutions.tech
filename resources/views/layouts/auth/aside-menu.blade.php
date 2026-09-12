<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 flex flex-col"
    style="background: linear-gradient(180deg, #0b2545 0%, #003b70 60%, #0a3060 100%);"
    aria-label="Sidebar">

    <!-- ═══ Fixed Logo Area ═══ -->
    <div class="flex-shrink-0 px-5 py-5 border-b" style="border-color: rgba(255,255,255,0.08);">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('assets/img/logo/white-logo.png') }}"
                 class="h-10 w-auto object-contain"
                 alt="UDS Logo" />
        </a>
        <!-- Tagline -->
        <p class="mt-2 text-[9px] font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.35);">
            Property Management Portal
        </p>
    </div>

    <!-- ═══ Scrollable Navigation ═══ -->
    <div class="flex-grow overflow-y-auto px-3 py-4 space-y-1" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent;">

        @php use App\Enums\UserRole; @endphp

        {{-- ── Dashboard ── --}}
        @php $isDash = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}" wire:navigate.hover
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150 group"
            style="{{ $isDash
                ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                : 'color:rgba(255,255,255,0.7);' }}"
            onmouseover="{{ $isDash ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
            onmouseout="{{ $isDash ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
            <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                style="{{ $isDash ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                <i class="fas fa-tachometer-alt text-xs" style="{{ $isDash ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
            </span>
            <span>Dashboard</span>
            @if($isDash)
                <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
            @endif
        </a>

        {{-- ── ADMIN ── --}}
        @if (Auth::user()->userRole === UserRole::ADMIN->value)
            @php $isAdminReq = request()->routeIs('admin.user.request'); @endphp
            <a href="{{ route('admin.user.request') }}" wire:navigate.hover
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150"
                style="{{ $isAdminReq
                    ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                    : 'color:rgba(255,255,255,0.7);' }}"
                onmouseover="{{ $isAdminReq ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
                onmouseout="{{ $isAdminReq ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
                <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                    style="{{ $isAdminReq ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                    <i class="fas fa-users text-xs" style="{{ $isAdminReq ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
                </span>
                <span>User Requests</span>
                @if($isAdminReq)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
                @endif
            </a>
        @endif

        {{-- ── LANDLORD ── --}}
        @if (Auth::user()->userRole === UserRole::LANDLORD->value)

            {{-- Section: Users --}}
            <div class="pt-4 pb-1 px-3">
                <span class="text-[9px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.3);">Users</span>
            </div>

            @php
                $navItems = [
                    ['route' => 'landlord.property.manager', 'icon' => 'fa-user-tie',        'label' => 'Manager(s)'],
                    ['route' => 'landlord.tenant',           'icon' => 'fa-user-friends',     'label' => 'Client'],
                ];
            @endphp
            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" wire:navigate.hover
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150"
                    style="{{ $active
                        ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                        : 'color:rgba(255,255,255,0.7);' }}"
                    onmouseover="{{ $active ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
                    onmouseout="{{ $active ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                        style="{{ $active ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                        <i class="fas {{ $item['icon'] }} text-xs" style="{{ $active ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
                    </span>
                    <span>{{ $item['label'] }}</span>
                    @if($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
                    @endif
                </a>
            @endforeach

            {{-- Section: Business Management --}}
            <div class="pt-4 pb-1 px-3">
                <span class="text-[9px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.3);">Business Management</span>
            </div>

            @php
                $bizItems = [
                    ['route' => 'landlord.property',   'icon' => 'fa-building',              'label' => 'Property'],
                    ['route' => 'landlord.RentRecord',  'icon' => 'fa-door-open',             'label' => 'Rent Records'],
                    ['route' => 'landlord.invoice',     'icon' => 'fa-file-invoice-dollar',   'label' => 'Invoice'],
                ];
            @endphp
            @foreach($bizItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" wire:navigate.hover
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150"
                    style="{{ $active
                        ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                        : 'color:rgba(255,255,255,0.7);' }}"
                    onmouseover="{{ $active ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
                    onmouseout="{{ $active ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                        style="{{ $active ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                        <i class="fas {{ $item['icon'] }} text-xs" style="{{ $active ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
                    </span>
                    <span>{{ $item['label'] }}</span>
                    @if($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
                    @endif
                </a>
            @endforeach

            {{-- Section: Setting --}}
            <div class="pt-4 pb-1 px-3">
                <span class="text-[9px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.3);">Setting</span>
            </div>

            @php $isPayment = request()->routeIs('landlord.paymentmode'); @endphp
            <a href="{{ route('landlord.paymentmode') }}" wire:navigate.hover
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150"
                style="{{ $isPayment
                    ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                    : 'color:rgba(255,255,255,0.7);' }}"
                onmouseover="{{ $isPayment ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
                onmouseout="{{ $isPayment ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
                <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                    style="{{ $isPayment ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                    <i class="fas fa-credit-card text-xs" style="{{ $isPayment ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
                </span>
                <span>Payment Mode</span>
                @if($isPayment)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
                @endif
            </a>

            {{-- Section: Report --}}
            <div class="pt-4 pb-1 px-3">
                <span class="text-[9px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.3);">Report</span>
            </div>

            @php
                $reportItems = [
                    ['route' => 'landlord.rental-income-tax', 'icon' => 'fa-percent',          'label' => 'Rental Income Tax'],
                    ['route' => 'landlord.vat',               'icon' => 'fa-receipt',          'label' => 'VAT'],
                    ['route' => 'landlord.property-tax',      'icon' => 'fa-hand-holding-usd', 'label' => 'Property Tax'],
                    ['route' => 'landlord.tax-calculator',    'icon' => 'fa-calculator',        'label' => 'Tax Calculator'],
                    ['route' => 'landlord.crm-analytics',     'icon' => 'fa-chart-line',        'label' => 'CRM Analytics'],
                ];
            @endphp
            @foreach($reportItems as $item)
                @php
                    $active = isset($item['href']) ? false : request()->routeIs($item['route']);
                    $href   = isset($item['href']) ? $item['href'] : route($item['route']);
                @endphp
                <a href="{{ $href }}" @if(!isset($item['href'])) wire:navigate.hover @endif
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition duration-150"
                    style="{{ $active
                        ? 'background:rgba(243,146,0,0.18); color:#f39200;'
                        : 'color:rgba(255,255,255,0.7);' }}"
                    onmouseover="{{ $active ? '' : "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" }}"
                    onmouseout="{{ $active ? '' : "this.style.background='transparent';this.style.color='rgba(255,255,255,0.7)'" }}">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0"
                        style="{{ $active ? 'background:rgba(243,146,0,0.25);' : 'background:rgba(255,255,255,0.07);' }}">
                        <i class="fas {{ $item['icon'] }} text-xs" style="{{ $active ? 'color:#f39200' : 'color:rgba(255,255,255,0.5)' }}"></i>
                    </span>
                    <span>{{ $item['label'] }}</span>
                    @if($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full shrink-0" style="background:#f39200;"></span>
                    @endif
                </a>
            @endforeach

        @endif
    </div>

    <!-- ═══ Bottom Brand Footer ═══ -->
    <!-- <div class="flex-shrink-0 px-5 py-4 border-t" style="border-color: rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                 style="background:rgba(243,146,0,0.20);">
                <i class="fas fa-building text-xs" style="color:#f39200;"></i>
            </div>
            <div>
                <p class="text-[10px] font-extrabold" style="color:rgba(255,255,255,0.85);">
                    {{ Auth::user()->name ?? 'Landlord' }}
                </p>
                <p class="text-[9px] font-bold" style="color:rgba(255,255,255,0.35);">Landlord Portal</p>
            </div>
        </div>
    </div> -->

</aside>
