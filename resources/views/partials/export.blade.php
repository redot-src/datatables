<div class="dropdown">
    <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
        <i class="fas fa-file-export"></i>
    </a>

    <div class="dropdown-menu">
        @if (in_array('pdf', $allowedExports))
            <a class="dropdown-item" href="#" wire:click.prevent="toPdf">
                <span class="dropdown-item-icon">
                    <i class="fas fa-file-pdf"></i>
                </span>

                <span class="dropdown-item-title">
                    @lang('datatables::datatable.exports.pdf')
                </span>
            </a>
        @endif 

        @if (in_array('xlsx', $allowedExports))
            <a class="dropdown-item" href="#" wire:click.prevent="toXlsx">
                <span class="dropdown-item-icon">
                    <i class="fas fa-file-excel"></i>
                </span>

                <span class="dropdown-item-title">
                    @lang('datatables::datatable.exports.excel')
                </span>
            </a>
        @endif 

        @if (in_array('csv', $allowedExports))
            <a class="dropdown-item" href="#" wire:click.prevent="toCsv">
                <span class="dropdown-item-icon">
                    <i class="fas fa-file-csv"></i>
                </span>

                <span class="dropdown-item-title">
                    @lang('datatables::datatable.exports.csv')
                </span>
            </a>
        @endif 

        @if (in_array('json', $allowedExports))
            <a class="dropdown-item" href="#" wire:click.prevent="toJson">
                <span class="dropdown-item-icon">
                    <i class="fas fa-file-code"></i>
                </span>

                <span class="dropdown-item-title">
                    @lang('datatables::datatable.exports.json')
                </span>
            </a>
        @endif 
    </div>
</div>
