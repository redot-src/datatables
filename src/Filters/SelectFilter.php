<?php

namespace Redot\Datatables\Filters;

use Illuminate\Support\Collection;

class SelectFilter extends Filter
{
    /**
     * The filter's options.
     */
    public array|Collection $options = [];

    /**
     * The select placeholder.
     */
    public ?string $placeholder = null;

    /**
     * The filter's view.
     */
    public ?string $view = 'datatables::filters.select';

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->placeholder = __('datatables::datatable.filters.select');

        $this->queryCallback = function ($query, $value) {
            $query->where($this->column, $value);
        };
    }

    /**
     * Set the filter's options.
     */
    public function options(array|Collection $options): self
    {
        $this->options = $options;

        return $this;
    }
}
