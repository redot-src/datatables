<div class="dropdown">
    <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fas fa-file-export me-2"></i>
        @lang('datatables::datatable.export')
    </a>

    <div class="dropdown-menu">
        <a class="dropdown-item" href="#" wire:click.prevent="toPdf">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-pdf"></i>
            </span>

            @lang('datatables::datatable.exports.pdf')
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toXlsx">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-excel"></i>
            </span>

            @lang('datatables::datatable.exports.excel')
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toCsv">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-csv"></i>
            </span>

            @lang('datatables::datatable.exports.csv')
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toJson">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-code"></i>
            </span>

            @lang('datatables::datatable.exports.json')
        </a>
    </div>
</div>
