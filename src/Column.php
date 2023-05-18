<?php

namespace Redot\LivewireDatatable;

use Closure;
use Illuminate\Support\Traits\Macroable;

class Column
{
    use Macroable;

    /**
     * Column field.
     */
    public string|null $field;

    /**
     * Column label.
     */
    public string $label;

    /**
     * Column classes.
     */
    public string $class = '';

    /**
     * Column width.
     */
    public string $width = 'auto';

    /**
     * Column searchable.
     */
    public bool $searchable = false;

    /**
     * Column sortable.
     */
    public bool $sortable = false;

    /**
     * Column resolve callback.
     */
    public Closure|null $resolver = null;

    /**
     * Column format callback.
     */
    public Closure|null $formatter = null;

    /**
     * Column constructor.
     */
    public function __construct(string $label, string $field = null)
    {
        $this->label = $label;
        $this->field = $field;
    }

    /**
     * Make new column.
     */
    public static function make(string $label, string $field = null): static
    {
        return new static($label, $field);
    }

    /**
     * Set column classes.
     */
    public function class(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Set column width.
     */
    public function width(string $width): static
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Make column searchable.
     */
    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Make column sortable.
     */
    public function sortable(bool $sortable = true): static
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Format column value.
     */
    public function format(Closure $formatter): static
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Resolve column value.
     */
    public function resolve(Closure $resolver): static
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * Get column value.
     */
    public function value(mixed $row): mixed
    {
        if ($this->resolver) {
            return call_user_func($this->resolver, $row);
        }

        $value = data_get($row, $this->field);

        if ($this->formatter) {
            return call_user_func($this->formatter, $value);
        }

        return $value;
    }
}
