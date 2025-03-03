<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    /**
     * The filter's label.
     *
     * @var string|null
     */
    public string|null $label = null;

    /**
     * The filter's column.
     *
     * @var string|null
     */
    public string|null $column = null;

    /**
     * The filter's active state.
     *
     * @var bool
     */
    public bool $isActive = false;

    /**
     * The filter's query callback.
     *
     * @var Closure|null
     */
    public Closure|null $queryCallback = null;

    /**
     * Make a new filter instance.
     *
     * @param string|null $label
     * @param string|null $column
     * @return static
     */
    public static function make(string $label = null, string $column = null): Filter
    {
        return new static($label, $column);
    }

    /**
     * Create a new filter instance.
     *
     * @param string|null $label
     * @param string|null $column
     * @return void
     */
    public function __construct(string|null $label = null, string|null $column = null)
    {
        $this->label = $label;
        $this->column = $column;
    }

    /**
     * Set the filter's label.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): Filter
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the filter's column.
     *
     * @param string $column
     * @return $this
     */
    public function column(string $column): Filter
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Mark the filter as active.
     *
     * @return $this
     */
    public function active(): Filter
    {
        $this->isActive = true;

        return $this;
    }

    /**
     * Modify the base query.
     *
     * @param Closure $callback
     * @return $this
     */
    public function query(Closure $callback): Filter
    {
        $this->queryCallback = $callback;

        return $this;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Builder $query
     * @return void
     */
    public function apply(Builder $query): void
    {
        if ($this->queryCallback) {
            $this->queryCallback->call($this, $query);
        }
    }
}
