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
    @if ($icon && str_starts_with($icon, '<'))
        {!! $icon !!}
    @elseif ($icon)
        <i @class([$icon, 'dropdown-item-icon'])></i>
    @endif

    @if ($title)
        {{ $title }}
    @endif
</a>
