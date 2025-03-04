<?php

namespace Redot\Datatables\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;

trait BuildAttributes
{
    /**
     * The column's content class.
     */
    public string|array $class = [];

    /**
     * The column css styles.
     */
    public string|array $css = [];

    /**
     * The column html attributes.
     */
    public array $attributes = [];

    /**
     * Build attributes for the column.
     */
    public function buildAttributes(?Model $row = null): ComponentAttributeBag
    {
        if (method_exists($this, 'prepareAttributes')) {
            $this->prepareAttributes($row);
        }

        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            $attributes[$key] = is_callable($value) ? $value($row) : $value;
        }

        $classes = Arr::wrap($this->class);
        foreach ($classes as $key => $value) {
            $classes[$key] = is_callable($value) ? $value($row) : $value;
        }

        $styles = Arr::wrap($this->css);
        foreach ($styles as $key => $value) {
            $styles[$key] = is_callable($value) ? $value($row) : $value;
        }

        return (new ComponentAttributeBag($attributes))
            ->class($classes)
            ->style($styles);
    }
}
