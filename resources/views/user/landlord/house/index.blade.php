<x-app-layout>
    <!-- Navigation Tabs & Header -->
    <div class="mb-6 p-4 bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.01)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex flex-wrap gap-2 p-1 bg-slate-100/80 rounded-xl">
                <a href="{{ route('landlord.property') }}"
                   class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all duration-200 {{ request()->routeIs('landlord.property') ? 'bg-uds-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:text-uds-blue hover:bg-slate-200/70' }}"
                   wire:navigate.hover>
                    <i class="mr-2 fas fa-building"></i>
                    <span>Properties (Land)</span>
                </a>
                <a href="{{ route('landlord.house') }}"
                   class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all duration-200 {{ request()->routeIs('landlord.house') ? 'bg-uds-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:text-uds-blue hover:bg-slate-200/70' }}"
                   wire:navigate.hover>
                    <i class="mr-2 fas fa-home"></i>
                    <span>Buildings</span>
                </a>
                <a href="{{ route('landlord.unit') }}"
                   class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all duration-200 {{ request()->routeIs('landlord.unit') ? 'bg-uds-blue text-white shadow-md shadow-blue-900/10' : 'text-slate-600 hover:text-uds-blue hover:bg-slate-200/70' }}"
                   wire:navigate.hover>
                    <i class="mr-2 fas fa-door-open"></i>
                    <span>Units</span>
                </a>
            </div>
            
            <!-- Context Info -->
            <div class="text-right hidden sm:block">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Asset Directory</p>
                <p class="text-xs font-semibold text-slate-600">Landlord Portfolio</p>
            </div>
        </div>
    </div>

    <!-- Livewire Content -->
    @livewire('user.land-lord.house.house-crud-livewire')
</x-app-layout>
