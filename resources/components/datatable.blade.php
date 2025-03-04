@assets
    <link rel="stylesheet" href="{{ route('__datatables.css', ['v' => $jsAssetsVersionHash]) }}">
    <script src="{{ route('__datatables.js', ['v' => $cssAssetsVersionHash]) }}"></script>
@endassets

<div class="card datatable" @style(['max-height: ' . $height]) id="{{ $id }}">
    <div class="card-body d-flex align-items-center gap-1 border-bottom" wire:ignore>
        <div class="w-auto me-auto">
            @include('datatables::partials.per-page')
        </div>

        @if ($searchable)
            <div>
                @include('datatables::partials.search')
            </div>
        @endif

        @if ($exportable)
            <div>
                @include('datatables::partials.export')
            </div>
        @endif

        <div>
            @include('datatables::partials.refresh')
        </div>
    </div>

    @include('datatables::partials.table')

    <div class="card-footer">
        @include('datatables::partials.pagination')
    </div>
</div>
