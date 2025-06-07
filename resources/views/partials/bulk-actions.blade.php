<div class="dropdown" wire:key="bulk-actions">
    <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
        <i class="fas fa-list"></i>
    </a>

    <div class="dropdown-menu">
        @foreach ($bulkActions as $action)
            <a {{ $action->buildAttributes() }}>
                @if ($action->icon)
                    <span class="dropdown-item-icon">
                        <i class="{{ $action->icon }}"></i>
                    </span>
                @endif

                <span class="dropdown-item-title">
                    {{ $action->label }}
                </span>
            </a>
        @endforeach
    </div>
</div>

