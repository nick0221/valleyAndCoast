@php
    $brandName = filament()->getBrandName();
    $brandLogo = filament()->getBrandLogo()
@endphp

@if (filled($brandLogo))
    <img
        src="{{ $brandLogo }}"
        loading="lazy"
        alt="{{ $brandName }}"
        class="w-36  {{ (request()->path() === 'admin/login') ? ' ' : 'h-16' }} "

        {{$attributes->class(['fi-logo']) }}
    />

@else
    <div
        {{ $attributes->class(['fi-logo text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white']) }}
    >
        {{ $brandName }}
    </div>
@endif
