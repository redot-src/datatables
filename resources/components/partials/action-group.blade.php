<div class="dropdown">
    <button type="button" {{ $action->buildAttributes($row) }}>
        @if ($action->icon)
            <i @class([$action->icon, 'me-2' => $action->label])></i>
        @endif

        @if ($action->label)
            <span>{{ $action->label }}</span>
        @endif
    </button>

    <div class="dropdown-menu">
        @foreach ($action->actions as $action)
            @include('datatables::partials.action', [
                'action' => $action,
                'row' => $row,
            ])
        @endforeach
    </div>
</div>
