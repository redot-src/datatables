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

        // Apply the filter to the query.
        $this->withRelation($this->column, $query, function (Builder $query) use ($from, $to) {
            if ($from && ! $to) {
                $query->whereDate($this->column, '>=', $from);
            } elseif (! $from && $to) {
                $query->whereDate($this->column, '<=', $to);
            } elseif ($from && $to) {
                $query->whereBetween($this->column, [$from, $to]);
            }
        });
    }
}
