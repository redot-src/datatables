@props(['url'])

<a href="{{ $url }}" class="btn btn-icon" datatable-action="view" data-bs-toggle="tooltip"
    title="{{ __('View') }}" data-bs-placement="bottom" {{ $attributes }}>
    @include('livewire-datatable::icons.eye')
</a>
