@props([
    'href' => '#',
    'icon' => '',
    'title' => '',
    'class' => 'btn',
    'attrs' => [],
])

@php
    $method = strtoupper($method);
    $attrs = collect($attrs)->map(fn($v, $k) => $k . '="' . $v . '"')->implode(' ');
@endphp

<a href="{{ $href }}" @class([$class, 'btn-icon' => $icon]) title="{{ $title }}"
    @if ($icon) data-bs-toggle="tooltip" data-bs-placement="bottom" @endif {{ $attributes }}
    {!! $attrs !!}>
    {!! $icon ?: $title !!}
</a>
