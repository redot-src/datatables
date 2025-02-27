<div class="card datatable" id="{{ $id }}">
    <table class="card-table table">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column->label }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody wire:loading.class="opacity-50">
            @forelse($rows as $row)
                <tr>
                    @foreach($columns as $column)
                        <td @style($column->css) @class($column->class) {{ $column->buildAttributes($row) }}>
                            @if ($column->html)
                                {!! $column->get($row) !!}
                            @else
                                {{ $column->get($row) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $colspan }}" class="text-center text-muted">
                        @lang('datatables::pagination.empty')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="card-footer d-flex gap-5 align-items-center justify-content-between">
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select w-auto" wire:model.live="perPage">
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>

            <div class="text-muted">
                @lang('datatables::pagination.showing', [
                    'first' => $rows->firstItem(),
                    'last' => $rows->lastItem(),
                    'total' => $rows->total(),
                ])
            </div>
        </div>

        <div class="my-1">{{ $rows->links() }}</div>
    </div>
</div>