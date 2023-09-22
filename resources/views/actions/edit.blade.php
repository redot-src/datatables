@props(['url'])

<a href="{{ $url }}" class="btn btn-icon" datatable-action="edit" data-bs-toggle="tooltip"
    title="{{ __('Edit') }}" data-bs-placement="bottom" {{ $attributes }}>
    @include('livewire-datatable::icons.pencil')
</a>
