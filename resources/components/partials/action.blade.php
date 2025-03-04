@props(['action', 'row', 'grouped' => false])

@if ($grouped)
    <a class="dropdown-item">
        @if ($action->icon)
            <i class="{{ $action->icon }}"></i>
        @endif

        @if ($action->label)
            <span>{{ $action->label }}</span>
        @endif
    </a>
@else
    <a class="btn" {{ $action->buildAttributes($row) }}>
        <i class="{{ $action->icon }}"></i>
    </a>
@endif