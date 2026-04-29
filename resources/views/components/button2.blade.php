{{-- resources/views/components/button.blade.php --}}
@props([
    'disabled' => false,
    'type' => 'button',
    'action' => null,
    'icon' => null,
    'loadingText' => 'Loading...',
    'color' => 'blue-600',
    'colorClasses' => 'bg-blue-600 text-white hover:bg-blue-700',
    'sizeClasses' => 'px-4 py-2 text-sm',
])

<button
    {{ $attributes->merge([
        'type' => $type,
        'class' =>
            $colorClasses .
            ' ' .
            $sizeClasses .
            ' rounded font-medium transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-' .
            str_replace('-600', '', $color) .
            '-500',
    ]) }}
    @if ($action) wire:click="{{ $action }}" @endif {{ $disabled ? 'disabled' : '' }}>

    @if ($action)
        <span wire:loading.remove wire:target="{{ $action }}">
            @if ($icon)
                <i class="{{ $icon }} mr-1"></i>
            @endif
            {{ $slot }}
        </span>
        <span wire:loading wire:target="{{ $action }}">
            <i class="mr-2 fas fa-spinner fa-spin"></i>{{ $loadingText }}
        </span>
    @else
        @if ($icon)
            <i class="{{ $icon }} mr-1"></i>
        @endif
        {{ $slot }}
    @endif
</button>
