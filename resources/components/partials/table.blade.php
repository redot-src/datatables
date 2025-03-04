<div class="table-responsive">
    <table @class(['table card-table table-vcenter datatable', 'bordered' => $bordered])>
        <thead @class(['sticky-top' => $stickyHeader])>
            <tr>
                @foreach ($columns as $column)
                    <th @class(['fixed-' . $column->fixedDirection => $column->fixed])>
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

                @if ($actions)
                    <th class="w-1 fixed-end datatable-actions"></th>
                @endif
            </tr>
        </thead>

        <tbody wire:loading.class="opacity-50">
            @forelse($rows as $row)
                <tr>
                    @foreach ($columns as $column)
                        <td {{ $column->buildAttributes($row) }}>
                            @if ($column->html)
                                {!! $column->get($row) !!}
                            @else
                                {{ $column->get($row) }}
                            @endif
                        </td>
                    @endforeach

                    @if ($actions)
                        <td class="fixed-end datatable-actions">
                            @foreach ($actions as $action)
                                @if ($action->isActionGroup)
                                    @include('datatables::partials.action-group', ['group' => $action, 'row' => $row])
                                @else
                                    @include('datatables::partials.action', ['action' => $action, 'row' => $row, 'grouped' => false])
                                @endif
                            @endforeach
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $colspan }}" class="text-center text-muted">
                        @include('datatables::partials.empty')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
