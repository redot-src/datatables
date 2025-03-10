<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TernaryFilter extends Filter
{
    /**
     * The filter's view.
     */
    public string $view = 'datatables::filters.ternary';

    /**
     * Queries that can be performed on the filter.
     */
    public array $queries;

    /**
     * Labels for the filter's options.
     */
    public array $labels;

    /**
     * The select placeholder.
     */
    public string $placeholder;

    /**
     * Determine if the filter has null option.
     */
    public bool $empty = false;

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        $this->queries = [
            'yes' => fn (Builder $query) => $query->where($this->column, true),
            'no' => fn (Builder $query) => $query->where($this->column, false),
            'empty' => fn (Builder $query) => $query->whereNull($this->column),
        ];

        $this->labels = [
            'yes' => __('datatables::datatable.filters.ternary.yes'),
            'no' => __('datatables::datatable.filters.ternary.no'),
            'empty' => __('datatables::datatable.filters.ternary.empty'),
        ];

        $this->placeholder = __('datatables::datatable.filters.ternary.placeholder');
    }

    /**
     * Set the filter's queries.
     */
    public function queries(?Closure $yes = null, ?Closure $no = null, ?Closure $empty = null): self
    {
        if ($yes) {
            $this->queries['yes'] = $yes;
        }

        if ($no) {
            $this->queries['no'] = $no;
        }

        if ($empty) {
            $this->queries['empty'] = $empty;
        }

        return $this;
    }

    /**
     * Set the filter's labels.
     */
    public function labels(?string $yes = null, ?string $no = null, ?string $empty = null): self
    {
        if ($yes) {
            $this->labels['yes'] = $yes;
        }

        if ($no) {
            $this->labels['no'] = $no;
        }

        if ($empty) {
            $this->labels['empty'] = $empty;
        }

        return $this;
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
     * Set the filter's null option.
     */
    public function empty(bool $empty = true): self
    {
        $this->empty = $empty;

        return $this;
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, $value): void
    {
        if (! in_array($value, array_keys($this->queries))) {
            return;
        }

        call_user_func($this->queries[$value], $query);
    }
}
