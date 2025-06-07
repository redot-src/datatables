@props(['bulkAction'])

<button type="button" {{ $bulkAction->buildAttributes() }}>
    @if ($bulkAction->icon)
        <span>
            <i @class([$bulkAction->icon, 'me-2' => $bulkAction->label])></i>
        </span>
    @endif

    @if ($bulkAction->label)
        <span>{{ $bulkAction->label }}</span>
    @endif
</button> 