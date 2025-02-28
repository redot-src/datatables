<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap datatable">
        <thead @class(['sticky-top' => $stickyHeader])>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column->label }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody wire:loading.class="opacity-50">
            @forelse($rows as $row)
                <tr>
                    @foreach ($columns as $column)
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
                        @lang('datatables::datatable.pagination.empty')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
