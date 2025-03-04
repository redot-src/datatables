@props(['action', 'row'])

<a {{ $action->buildAttributes($row) }}>
    @if ($action->icon)
        <span @class(['dropdown-item-icon' => $action->grouped])>
            <i @class([$action->icon])></i>
        </span>
    @endif

    @if ($action->label && $action->grouped)
        <span @class([
            'dropdown-item-title' => $action->grouped,
            'ms-2' => ! $action->grouped && $action->icon,
        ])>
            {{ $action->label }}
        </span>
    @endif
</a>
