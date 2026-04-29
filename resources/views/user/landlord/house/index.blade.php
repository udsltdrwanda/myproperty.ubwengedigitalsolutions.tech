<x-app-layout>
    <!-- Breadcrumb and Navigation Header -->
    <div class="p-4 bg-white rounded-lg shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('landlord.property') }}"
               class="flex items-center px-4 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('landlord.property') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100' }}"
               wire:navigate.hover>
                <i class="mr-2 fa fa-building"></i>
                <span>Properties</span>
            </a>
            <a href="{{ route('landlord.house') }}"
               class="flex items-center px-4 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('landlord.house') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100' }}"
               wire:navigate.hover>
                <i class="mr-2 fa fa-home"></i>
                <span>Houses</span>
            </a>
            <a href="{{ route('landlord.unit') }}"
               class="flex items-center px-4 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('landlord.unit') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100' }}"
               wire:navigate.hover>
                <i class="mr-2 fa fa-door-open"></i>
                <span>Units</span>
            </a>
        </div>
    </div>

    <!-- Content Container -->
    <div class="container p-6 mt-4 bg-white rounded-lg shadow-md">
        <div class="content">
            @livewire('user.land-lord.house.house-crud-livewire')
        </div>
    </div>
</x-app-layout>
