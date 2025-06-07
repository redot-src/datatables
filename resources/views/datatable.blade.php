@assets
    <link rel="stylesheet" href="{{ $cssAssetsUrl }}" />
    <script src="{{ $jsAssetsUrl }}" defer></script>
@endassets

<div class="card datatable" @style(['max-height: ' . $height]) id="{{ $id }}" wire:ignore.self x-data="{ filtersOpen: {{ $filtersOpen ? 'true' : 'false' }} }">
    <div class="card-body d-flex align-items-center gap-1">
        <div class="w-auto me-auto" wire:ignore>
            @include('datatables::partials.per-page')
        </div>

        @if ($searchable)
            <div wire:ignore>
                @include('datatables::partials.search')
            </div>
        @endif

        @if ($exportable)
            <div wire:ignore>
                @include('datatables::partials.export')
            </div>
        @endif

        @if ($filterable)
            <div class="d-flex align-items-center gap-1">
                @include('datatables::partials.filters-toggle')
            </div>
        @endif

        <div wire:ignore>
            @include('datatables::partials.refresh')
        </div>
    </div>

    @if ($filterable)
        <div class="card-body" wire:ignore x-show="filtersOpen" x-cloak>
            @include('datatables::partials.filters')
        </div>
    @endif

    {{-- Bulk Actions and Selection Status --}}
    @if(count($selectedRows) > 0)
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-muted">
                    @if($selectAll)
                        <span>All <strong>{{ count($selectedRows) }}</strong> items selected.</span>
                        <button type="button" class="btn btn-sm btn-light ms-2" wire:click="toggleSelectAll">
                            Deselect All
                        </button>
                    @else
                        <span><strong>{{ count($selectedRows) }}</strong> items selected.</span>
                        @php
                            // Get total count of items matching current query for "Select All X items" message
                            // This might require an efficient way to get count without fetching all data
                            // For now, let's assume a method like `getTotalQueryCount()` exists or will be added
                            // As a temporary placeholder, or if not easily available, omit the total count
                            // $totalQueryCount = $this->getTotalQueryCount();
                            // For now, let's make the "Select all" button generic without the total count
                            // to avoid adding a new count query at this stage.
                        @endphp
                        <button type="button" class="btn btn-sm btn-light ms-2" wire:click="toggleSelectAll">
                            Select all matching current query
                        </button>
                    @endif
                </div>
                <div class="ms-auto text-muted">
                    @if(count($bulkActions) > 0)
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                                @foreach($bulkActions as $action)
                                    <li>
                                        <a class="dropdown-item" href="#"
                                           wire:click.prevent="executeBulkAction('{{ $action->name }}')"
                                           {!! $action->buildActionAttributes($selectedRows) !!}>
                                            @if($action->icon)<i class="{{ $action->icon }} me-2"></i>@endif
                                            {{ $action->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @include('datatables::partials.table')

    <div class="card-footer">
        @include('datatables::partials.pagination')
    </div>
</div>
