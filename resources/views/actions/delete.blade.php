@props(['url'])

<form action="{{ $url }}" method="POST" class="m-0">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-icon" datatable-action="delete" data-bs-toggle="tooltip"
        title="{{ __('Delete') }}" data-bs-placement="bottom" {{ $attributes }}>
        @include('livewire-datatable::icons.trash')
    </button>
</form>
