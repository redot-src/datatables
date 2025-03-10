<?php

namespace Redot\Datatables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SelectFilter extends Filter
{
    /**
     * The select placeholder.
     */
    public ?string $placeholder = null;

    /**
     * The filter's options.
     */
    public array|Collection $options = [];

    /**
     * The filter's view.
     */
    public string $view = 'datatables::filters.select';

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->placeholder = __('datatables::datatable.filters.select.placeholder');
    }

    /**
     * Set the filter's placeholder.
     */
    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Set the filter's options.
     */
    public function options(array|Collection $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, $value): void
    {
        $query->where($this->column, $value);
    }
}
