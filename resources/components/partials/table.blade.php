<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap datatable">
        <thead @class(['sticky-top' => $stickyHeader])>
            <tr>
                @foreach ($columns as $column)
                    <th>
                        @if ($column->sortable && $column->name)
                            <a href="#" class="text-decoration-none text-muted"
                                wire:click.prevent="sort('{{ $column->name }}')">
                                <span class="me-1">
                                    {{ $column->label }}
                                </span>

                                @if ($sortColumn === $column->name)
                                    @if ($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        @else
                            {{ $column->label }}
                        @endif
                    </th>
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
