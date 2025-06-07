<div class="table-responsive border-top">
    <table @class([
        'table card-table table-vcenter datatable',
        'bordered' => $bordered,
    ])>
        @if ($rows->isEmpty() === false)
            <thead @class(['sticky-top' => $stickyHeader])>
                <tr>
                    @if ($bulkActionable)
                        <th class="w-1">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       wire:model.live="selectAll"
                                       wire:click="toggleSelectAll"
                                       title="{{ $selectAll ? __('datatables::datatable.bulk_actions.deselect_all') : __('datatables::datatable.bulk_actions.select_all') }}">
                            </div>
                        </th>
                    @endif

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
        @endif

        <tbody wire:loading.class="opacity-50">
            @forelse($rows as $row)
                <tr>
                    @if ($bulkActionable)
                        <td class="datatable-cell">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       value="{{ $row->getKey() }}"
                                       wire:model.live="selected"
                                       wire:click="toggleSelect({{ $row->getKey() }})">
                            </div>
                        </td>
                    @endif

                    @foreach ($columns as $column)
                        <td {{ $column->buildAttributes($row) }}>
                            {!! $column->get($row) !!}
                        </td>
                    @endforeach

                    @if ($actions)
                        <td class="fixed-end datatable-cell datatable-actions">
                            <div class="d-flex gap-1">
                                @foreach ($actions as $action)
                                    @if ($action->shouldRender($row))
                                        @if ($action->isActionGroup)
                                            @include('datatables::partials.action-group')
                                        @else
                                            @include('datatables::partials.action')
                                        @endif
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
