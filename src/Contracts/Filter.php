<?php

namespace Redot\Datatables\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Make a new filter instance.
     *
     * @param string|null $label
     * @param string|null $column
     * @return static
     */
    public static function make(string $label = null, string $column = null): Filter;

    /**
     * Set the filter's label.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): Filter;

    /**
     * Set the filter's column.
     *
     * @param string $column
     * @return $this
     */
    public function column(string $column): Filter;

    /**
     * Mark the filter as active.
     *
     * @return $this
     */
    public function active(): Filter;

    /**
     * Modify the base query.
     *
     * @param Closure $callback
     * @return $this
     */
    public function query(Closure $callback): Filter;

    /**
     * Apply the filter to the given query.
     *
     * @param Builder $query
     * @return void
     */
    public function apply(Builder $query): void;
}
