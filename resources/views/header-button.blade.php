@props([
    'href' => '#',
    'icon' => '',
    'title' => '',
    'attrs' => [],
])

@php
    $attrs = collect($attrs)->map(fn($v, $k) => $k . '="' . $v . '"')->implode(' ');
@endphp

<a href="{{ $href }}" class="dropdown-item" {{ $attributes }} {!! $attrs !!}>
    @if ($icon)
        <i @class([$icon, 'icon', 'dropdown-item-icon'])></i>
    @endif

    @if ($title)
        <span>{{ $title }}</span>
    @endif
</a>
