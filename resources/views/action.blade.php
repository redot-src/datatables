@props([
    'url' => '#',
    'title' => '',
    'icon' => '',
    'method' => 'GET',
    'attributes' => [],
])

<form action="{{ $url }}" method="{{ $method }}" class="m-0" {{ $attributes }}>
    @csrf
    @method($method)

    <button type="submit" class="btn btn-icon" data-bs-toggle="tooltip" title="{{ $title }}"
        data-bs-placement="bottom" {{ $attributes }}>
        {!! $icon !!}
    </button>
</form>
