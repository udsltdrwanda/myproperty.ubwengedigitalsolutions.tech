<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen  transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <div class="py-4 box-logo">
            <a href="{{ route('dashboard') }}" class="flex ms-2 md:me-24">
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="FlowBite Logo" />
            </a>
        </div>
        <hr>
        <ul class="py-4 space-y-2 font-medium">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                    wire:navigate.hover>
                    <i
                        class="w-5 h-5 text-gray-500 transition duration-75 fa fa-tachometer-alt dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            @php
                use App\Enums\UserRole;
            @endphp

            @if (Auth::user()->userRole === UserRole::ADMIN->value)
                <li>
                    <a href="{{ route('admin.user.request') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.user.request') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-users shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">User Request</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->userRole === UserRole::LANDLORD->value)
                <div class="">
                    <div class="flex w-full mb-4 text-xl text-gray-400 dark:text-gray-300 flex-c">
                        Users
                    </div>
                    <a href="{{ route('landlord.property.manager') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.property.manager') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Manager(s)</span>
                    </a>
                    <a href="{{ route('landlord.tenant') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.tenant') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Client</span>
                    </a>
                </div>
                <div class="">
                    <div class="flex w-full mb-4 text-xl text-gray-400 dark:text-gray-300 flex-c">
                        Business Management
                    </div>
                    <!-- Property -->

                    <a href="{{ route('landlord.property') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.property') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-users shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Property</span>
                    </a>

                    <a href="{{ route('landlord.RentRecord') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.RentRecord') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Tenant</span>
                    </a>

                    <a href="{{ route('landlord.invoice') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.invoice') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Invoice</span>
                    </a>

                </div>

                <div class="">
                    <div class="flex w-full mb-4 text-xl text-gray-400 dark:text-gray-300 flex-c">
                        Setting
                    </div>
                    <a href="{{ route('landlord.paymentmode') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.paymentmode') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Payment Mode</span>
                    </a>

                    <!-- Property -->

                    {{-- <a href="{{ route('landlord.amenity') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.amenity') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-users shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Property Amenity</span>
                    </a> --}}
                </div>
                <div class="">
                    <div class="flex w-full mb-4 text-xl text-gray-400 dark:text-gray-300 flex-c">
                        Report
                    </div>
                    <a href="{{ route('landlord.rental-income-tax') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.rental-income-tax') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Rental Income Tax</span>
                    </a>
                    <a href="{{ route('landlord.property-tax') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.property-tax') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-file-contract shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Property Tax</span>
                    </a>

                    <a href="{{ route('landlord.tax-calculator') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.tax-calculator') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-users shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Tax Calculator</span>
                    </a>

                    <!-- Property -->
                    <a href="#"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('landlord.property') ? 'bg-blue-100 text-blue-500' : 'hover:text-blue-500 text-custom-300 dark:text-gray-300' }}"
                        wire:navigate.hover>
                        <i
                            class="w-5 h-5 text-gray-500 transition duration-75 fa fa-users shrink-0 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Petty Cash Flow</span>
                    </a>
                </div>
            @endif
        </ul>
    </div>
</aside>
