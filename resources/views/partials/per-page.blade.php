<select class="form-select datatable-per-page" wire:model.live="perPage">
    @foreach ($perPageOptions as $option)
        <option value="{{ $option }}">{{ $option }}</option>
    @endforeach
</select>
