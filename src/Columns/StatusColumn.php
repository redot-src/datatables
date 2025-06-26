<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class StatusColumn extends Column
{
    /**
     * Class list for the element.
     */
    public array $class = ['text-center'];

    /**
     * The status labels map.
     */
    public array $labels = [];

    /**
     * The status classes map.
     */
    public array $classes = [];

    /**
     * Set the status labels map.
     */
    public function labels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Set the status classes map.
     */
    public function classes(array $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        return $this->labels[$value] ?? $value;
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        parent::prepareAttributes($row);

        $value = $this->get($row, true);

        if (isset($this->classes[$value])) {
            $this->class($this->classes[$value]);
        }
    }
} 