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
            $from = $value['from'] ?? null;
            $to = $value['to'] ?? null;

            if ($from && ! $to) {
                $query->whereDate($this->column, '>=', $from);
            } elseif (! $from && $to) {
                $query->whereDate($this->column, '<=', $to);
            } elseif ($from && $to) {
                $query->whereBetween($this->column, [$from, $to]);
            }
        };
    }
}
