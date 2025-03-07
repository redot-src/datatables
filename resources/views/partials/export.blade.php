<div class="dropdown">
    <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
        <i class="fas fa-file-export"></i>
    </a>

    <div class="dropdown-menu">
        <a class="dropdown-item" href="#" wire:click.prevent="toPdf">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-pdf"></i>
            </span>

            <span class="dropdown-item-title">
                @lang('datatables::datatable.exports.pdf')
            </span>
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toXlsx">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-excel"></i>
            </span>

            <span class="dropdown-item-title">
                @lang('datatables::datatable.exports.excel')
            </span>
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toCsv">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-csv"></i>
            </span>

            <span class="dropdown-item-title">
                @lang('datatables::datatable.exports.csv')
            </span>
        </a>

        <a class="dropdown-item" href="#" wire:click.prevent="toJson">
            <span class="dropdown-item-icon">
                <i class="fas fa-file-code"></i>
            </span>

            <span class="dropdown-item-title">
                @lang('datatables::datatable.exports.json')
            </span>
        </a>
    </div>
</div>
