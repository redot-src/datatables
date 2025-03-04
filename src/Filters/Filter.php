<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    /**
     * The filter's label.
     */
    public ?string $label = null;

    /**
     * The filter's column.
     */
    public ?string $column = null;

    /**
     * The filter's active state.
     */
    public bool $isActive = false;

    /**
     * The filter's query callback.
     */
    public ?Closure $queryCallback = null;

    /**
     * Create a new filter instance.
     */
    public function __construct(?string $label = null, ?string $column = null)
    {
        $this->label = $label;
        $this->column = $column;
    }

    /**
     * Make a new filter instance.
     */
    public static function make(?string $label = null, ?string $column = null): Filter
    {
        return new static($label, $column);
    }

    /**
     * Set the filter's label.
     */
    public function label(string $label): Filter
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the filter's column.
     */
    public function column(string $column): Filter
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Mark the filter as active.
     */
    public function active(bool $isActive = true): Filter
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Modify the base query.
     */
    public function query(Closure $callback): Filter
    {
        $this->queryCallback = $callback;

        return $this;
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query): void
    {
        if ($this->queryCallback) {
            call_user_func($this->queryCallback, $query);
        }
    }
}
