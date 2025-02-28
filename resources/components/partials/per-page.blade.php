<select class="form-select" wire:model.live="perPage">
    @foreach ($perPageOptions as $option)
        <option value="{{ $option }}">{{ $option }}</option>
    @endforeach
</select>
