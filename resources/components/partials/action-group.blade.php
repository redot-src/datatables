@props(['group', 'row'])

<div class="dropdown">
    <button type="button" {{ $group->buildAttributes($row) }}>
        @if ($group->icon)
            <i @class([$group->icon, 'me-2' => $group->label])></i>
        @endif

        @if ($group->label)
            <span>{{ $group->label }}</span>
        @endif
    </button>

    <div class="dropdown-menu">
        @foreach ($group->actions as $action)
            @include('datatables::partials.action', [
                'action' => $action,
                'row' => $row,
                'grouped' => true,
            ])
        @endforeach
    </div>
</div>
