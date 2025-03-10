<div class="input-group mb-3" id="filter-{{ $filter->index }}">
    <select class="form-select" wire:model.live="{{ $filter->wireKey }}.operator">
        @foreach ($filter->operators as $key => $operator)
            <option value="{{ $key }}">{{ $operator }}</option>
        @endforeach
    </select>

    <input type="number" class="form-control" wire:model.live="{{ $filter->wireKey }}.value">
</div>
