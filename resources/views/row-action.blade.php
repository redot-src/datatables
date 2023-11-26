@props([
    'href' => '#',
    'method' => 'GET',
    'icon' => '',
    'title' => '',
    'class' => 'btn',
    'attrs' => [],
])

@php
    $method = strtoupper($method);
    $attrs = collect($attrs)->map(fn($v, $k) => $k . '="' . $v . '"')->implode(' ');
@endphp

@if ($method === 'GET')
    <a href="{{ $href }}" @class([$class, 'btn-icon' => $icon]) title="{{ $title }}"
        @if ($icon) data-bs-toggle="tooltip" data-bs-placement="bottom" @endif {{ $attributes }}
        {!! $attrs !!}>
        {!! $icon ?: $title !!}
    </a>
@else
    <form action="{{ $href }}" method="POST" class="m-0" {!! $attrs !!}>
        @csrf
        @method($method)

        <button type="submit" @class([$class, 'btn-icon' => $icon]) title="{{ $title }}"
            @if ($icon) data-bs-toggle="tooltip" data-bs-placement="bottom" @endif {{ $attributes }}>
            {!! $icon ?: $title !!}
        </button>
    </form>
@endif
