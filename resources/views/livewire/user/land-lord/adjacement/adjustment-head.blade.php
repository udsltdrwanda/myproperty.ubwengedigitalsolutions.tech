<div class="flex justify-between p-4 bg-white border border-gray-100 rounded-sm shadow-md">
    <div class="flex flex-wrap items-center gap-3">
        {{-- <a href="{{ route('landlord.adjacement') }}"
            class="px-4 py-2  rounded-md transition-all duration-200 flex items-center {{ request()->routeIs('landlord.adjacement') ? 'bg-blue-50 text-blue-600 font-medium border-b-2 border-blue-500' : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-500' }}"
            wire:navigate.hover>
            <i class="mr-2 fas fa-table"></i>
            <span>District Tax Table</span>
        </a> --}}

        <a href="{{ route('landlord.land.adjacement') }}"
            class="px-4 py-2 rounded-md transition-all duration-200 flex items-center {{ request()->routeIs('landlord.land.adjacement') ? 'bg-blue-50 text-blue-600 font-medium border-b-2 border-blue-500' : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-500' }}"
            wire:navigate.hover>
            <i class="mr-2 fas fa-map"></i>
            <span>Land</span>
        </a>

        <a href="{{ route('landlord.building.adjacement') }}"
            class="px-4 py-2 rounded-md transition-all duration-200 flex items-center {{ request()->routeIs('landlord.building.adjacement') ? 'bg-blue-50 text-blue-600 font-medium border-b-2 border-blue-800' : 'bg-gray-50 text-gray-700 hover:bg-blue-50 hover:text-blue-500' }}"
            wire:navigate.hover>
            <i class="mr-2 fas fa-building"></i>
            <span>Building</span>
        </a>
    </div>

    <div>
        <a href="{{ route('landlord.property-tax') }}"
            class="flex items-center px-4 py-2 text-white transition-all duration-200 bg-blue-600 rounded-md hover:bg-blue-700"
            wire:navigate.hover>
            <i class="mr-2 text-sm fas fa-arrow-left"></i>
            <span>Back</span>
        </a>

    </div>
</div>
