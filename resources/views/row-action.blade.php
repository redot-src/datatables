@props([
    'href' => '#',
    'method' => 'GET',
    'icon' => '',
    'title' => '',
    'attrs' => [],
])

@php
    $method = strtoupper($method);
    $attrs = collect($attrs)->map(fn($v, $k) => $k . '="' . $v . '"')->implode(' ');
@endphp

@if ($method === 'GET')
    <a href="{{ $href }}" class="dropdown-item" {{ $attributes }} {!! $attrs !!}>
        @if ($icon)
            <i @class([$icon, 'icon', 'dropdown-item-icon'])></i>
        @endif

        @if ($title)
            <span>{{ $title }}</span>
        @endif
    </a>
@else
    <form action="{{ $href }}" method="POST" class="m-0" {!! $attrs !!}>
        @csrf
        @method($method)

        <button type="submit" class="dropdown-item" {{ $attributes }}>
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @else
                {{ $title }}
            @endif
        </button>
    </form>
@endif
