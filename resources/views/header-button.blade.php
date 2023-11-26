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
        <i class="{{ $icon }} me-2"></i>
    @endif

    @if ($title)
        {!! $title !!}
    @endif
</a>
