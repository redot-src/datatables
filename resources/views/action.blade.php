@props([
    'url' => '#',
    'title' => '',
    'icon' => '',
    'method' => 'GET',
    'attrs' => [],
])

@php
    $method = strtoupper($method);

    if (is_array($attrs)) {
        $attrs = collect($attrs)->map(fn ($value, $key) => $key . '="' . $value . '"')->implode(' ');
    }
@endphp

@if ($method === 'GET')
    <a href="{{ $url }}" class="btn btn-icon" data-bs-toggle="tooltip" title="{{ $title }}"
        data-bs-placement="bottom" {{ $attributes }}>
        {!! $icon !!}
    </a>
@else
    <form action="{{ $url }}" method="POST" class="m-0" {!! $attrs !!}>
        @csrf
        @method($method)

        <button type="submit" class="btn btn-icon" data-bs-toggle="tooltip" title="{{ $title }}"
            data-bs-placement="bottom" {{ $attributes }}>
            {!! $icon !!}
        </button>
    </form>
@endif
