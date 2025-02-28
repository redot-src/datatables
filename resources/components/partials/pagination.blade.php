<div class="row align-items-center justify-content-between p-1">
    <span class="col-lg text-secondary mb-3 mb-lg-0">
        @lang('datatables::datatable.pagination.showing', [
            'first' => $rows->firstItem() ?? 0,
            'last' => $rows->lastItem() ?? 0,
            'total' => $rows->total(),
        ])
    </span>

    <div class="col-lg-auto">
        {{ $rows->onEachSide(1)->links() }}
    </div>
</div>
