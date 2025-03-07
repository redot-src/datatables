<button type="button" @class(['btn btn-icon', 'active' => $showFilters]) wire:click="$toggle('showFilters')">
    <i class="fas fa-filter"></i>
</button>

@if ($filtered)
    <button type="button" class="btn btn-icon" wire:click="$set('filtered', [])">
        <i class="fas fa-close"></i>
    </button>
@endif
