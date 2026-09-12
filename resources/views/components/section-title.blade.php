<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-sm sm:text-base font-extrabold text-uds-navy tracking-tight">{{ $title }}</h3>

        <p class="mt-1.5 text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
