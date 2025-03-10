<?php

namespace Redot\Datatables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;
use Redot\Datatables\Traits\BuildAttributes;

abstract class Filter
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
    public string $view;

    /**
     * Create a new filter instance.
     */
    public function __construct(?string $column = null, ?string $label = null)
    {
        $this->index = ++static::$counter;
        $this->wireKey ??= sprintf('filtered.%s', $this->index);

        if ($column) {
            $this->column($column);
        }

        if ($label) {
            $this->label($label);
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
    public static function make(?string $column = null, ?string $label = null): Filter
    {
        return new static($column, $label);
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
     * Render the filter view.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view($this->view, ['filter' => $this]);
    }

    /**
     * Apply the filter to the given query.
     */
    abstract public function apply(Builder $query, mixed $value): void;
}
