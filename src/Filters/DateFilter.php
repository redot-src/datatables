<?php

namespace Redot\Datatables\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateFilter extends Filter
{
    /**
     * The filter's view.
     */
    public string $view = 'datatables::filters.date';

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, mixed $value): void
    {
        $from = $value['from'] ?? null;
        $to = $value['to'] ?? null;

        // Early return if the from and to are empty.
        if (! $from && ! $to) {
            return;
        }

        // Apply the filter to all columns with OR logic.
        $this->applyToColumns($query, function (Builder $query, string $column) use ($from, $to) {
            if ($from && ! $to) {
                $query->whereDate($column, '>=', $from);
            } elseif (! $from && $to) {
                $query->whereDate($column, '<=', $to);
            } elseif ($from && $to) {
                $query->whereBetween($column, [$from, $to]);
            }
        });
    }
}
