@if ($bulkActionable)
    <div class="card-body d-flex align-items-center gap-2" 
         x-show="$wire.selected.length > 0" 
         x-cloak
         style="background-color: var(--tblr-bg-surface-secondary);">
        
        <div class="me-auto">
            <span class="fw-medium">
                <span x-text="$wire.selected.length"></span>
                <span>{{ str_replace(':count', '', __('datatables::datatable.bulk_actions.selected_count')) }}</span>
            </span>
        </div>

        <div class="d-flex gap-1">
            @foreach ($bulkActions as $bulkAction)
                @if ($bulkAction->shouldRender())
                    @include('datatables::partials.bulk-action', [
                        'bulkAction' => $bulkAction,
                    ])
                @endif
            @endforeach
        </div>

        <button type="button" 
                class="btn btn-outline-secondary btn-sm"
                wire:click="clearSelection"
                title="{{ __('datatables::datatable.bulk_actions.clear_selection') }}">
            <i class="fas fa-times"></i>
            {{ __('datatables::datatable.bulk_actions.clear_selection') }}
        </button>
    </div>
@endif 