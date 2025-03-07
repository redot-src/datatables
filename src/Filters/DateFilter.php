<?php

namespace Redot\Datatables\Filters;

class DateFilter extends Filter
{
    /**
     * The filter's view.
     */
    public ?string $view = 'datatables::filters.date';

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->queryCallback = function ($query, $value) {
            $query->whereBetween($this->column, $value);
        };
    }
}
