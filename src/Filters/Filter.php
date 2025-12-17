<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;
use Redot\Datatables\Traits\BuildAttributes;
use Redot\Datatables\Traits\InteractsWithRelations;

abstract class Filter
{
    use BuildAttributes;
    use InteractsWithRelations;
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
     * The filter's column(s).
     */
    public string|array|null $column = null;

    /**
     * Determine if the filter columns should be applied with OR logic.
     */
    public bool $or = true;

    /**
     * Override the filter's query.
     */
    public ?Closure $query = null;

    /**
     * Determine if the filter should be applied globally.
     */
    public bool $global = false;

    /**
     * The filter's view.
     */
    public string $view;

    /**
     * Create a new filter instance.
     */
    public function __construct(string|array|null $column = null, ?string $label = null)
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
    public static function make(string|array|null $column = null, ?string $label = null): Filter
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
     * Set the filter's column(s).
     */
    public function column(string|array $column): Filter
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Set the filter's columns (alias for column with array).
     */
    public function columns(array $columns): Filter
    {
        $this->column = $columns;

        return $this;
    }

    /**
     * Set the filter's columns to be applied with OR logic.
     */
    public function or(bool $or = true): Filter
    {
        $this->or = $or;

        return $this;
    }

    /**
     * Set the filter's query.
     */
    public function query(Closure $query): Filter
    {
        $this->query = $query;

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
     * Get the columns as an array.
     */
    protected function getColumns(): array
    {
        if (is_array($this->column)) {
            return $this->column;
        }

        return $this->column ? [$this->column] : [];
    }

    /**
     * Apply the filter callback to all columns using OR logic.
     */
    protected function applyToColumns(Builder $query, Closure $callback): void
    {
        $columns = $this->getColumns();

        if (empty($columns)) {
            return;
        }

        // Wrap in where to group OR conditions
        $query->where(function (Builder $query) use ($columns, $callback) {
            foreach ($columns as $index => $column) {
                if ($index === 0 || ! $this->or) {
                    $this->withRelation($column, $query, $callback);
                } else {
                    $this->orWithRelation($column, $query, $callback);
                }
            }
        });
    }

    /**
     * Apply the filter to the given query.
     */
    abstract public function apply(Builder $query, mixed $value): void;
}
