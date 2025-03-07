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
                            {!! $column->get($row) !!}
                        </td>
                    @endforeach

                    @if ($actions)
                        <td class="fixed-end datatable-cell datatable-actions">
                            <div class="d-flex gap-1">
                                @foreach ($actions as $action)
                                    @if ($action->condition && ! call_user_func($action->condition, $row))
                                        @continue
                                    @endif

                                    @if ($action->isActionGroup)
                                        @include('datatables::partials.action-group')
                                    @else
                                        @include('datatables::partials.action')
                                    @endif
                                @endforeach
                            </div>
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
