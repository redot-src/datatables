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
        $attrs = collect($attrs)->map(function ($value, $key) {
            return $key . '="' . $value . '"';
        })->implode(' ');
    }
@endphp

<form action="{{ $url }}" method="{{ $method === 'GET' ? $method : 'POST' }}" class="m-0" {!! $attrs !!}>
    @csrf
    @method($method)

    <button type="submit" class="btn btn-icon" data-bs-toggle="tooltip" title="{{ $title }}"
        data-bs-placement="bottom" {{ $attributes }}>
        {!! $icon !!}
    </button>
</form>
