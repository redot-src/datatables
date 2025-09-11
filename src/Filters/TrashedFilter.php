<?php

namespace Redot\Datatables\Filters;

use Illuminate\Database\Eloquent\Builder;

class TrashedFilter extends Filter
{
    /**
     * Determine if the filter should be applied globally.
     */
    public bool $global = true;

    /**
     * The filter's view.
     */
    public string $view = 'datatables::filters.trashed';

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->label ??= __('datatables::datatable.filters.trashed.label');
        $this->column ??= 'deleted_at';
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, $value): void
    {
        match ($value) {
            'with' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => $query->withoutTrashed(),
        };
    }
}
