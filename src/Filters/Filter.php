<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;
use Redot\Datatables\Traits\BuildAttributes;

class Filter
{
    use BuildAttributes;
    use Macroable;

    /**
     * Static counter for filters.
     */
    public static int $counter = 0;

    /**
     * Unique identifier for the filter.
     */
    public int $index;

    /**
     * The filter's livewire key.
     */
    public string $wireKey;

    /**
     * The filter's label.
     */
    public ?string $label = null;

    /**
     * The filter's column.
     */
    public ?string $column = null;

    /**
     * The filter's view.
     */
    public ?string $view = null;

    /**
     * The filter's query callback.
     */
    public ?Closure $queryCallback = null;

    /**
     * Create a new filter instance.
     */
    public function __construct(?string $label = null, ?string $column = null)
    {
        $this->index = ++static::$counter;
        $this->wireKey ??= sprintf('filtered.%s', $this->index);

        if ($label) {
            $this->label($label);
        }

        if ($column) {
            $this->column($column);
        }

        $this->init();
    }

    /**
     * Initialize the filter.
     */
    protected function init(): void
    {
        //
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
     * Modify the base query.
     */
    public function query(Closure $callback): Filter
    {
        $this->queryCallback = $callback;

        return $this;
    }

    /**
     * Render the filter view.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view($this->view, ['filter' => $this]);
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, mixed $value): void
    {
        if ($this->queryCallback) {
            call_user_func($this->queryCallback, $query, $value);
        }
    }
}
