@props([
    'href' => '#',
    'icon' => '',
    'title' => '',
    'class' => 'btn btn-primary',
    'attrs' => [],
])

@php
    $attrs = collect($attrs)->map(fn($v, $k) => $k . '="' . $v . '"')->implode(' ');
@endphp

<a href="{{ $href }}" class="{{ $class }}" {{ $attributes }} {!! $attrs !!}>
    @if ($icon && str_starts_with($icon, '<'))
        {!! $icon !!}
    @elseif ($icon)
        <i class="{{ $icon }}"></i>
    @endif

    @if ($title)
        <span class="ms-2">{{ $title }}</span>
    @endif
</a>
