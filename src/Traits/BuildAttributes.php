<?php

namespace Redot\Datatables\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;

trait BuildAttributes
{
    /**
     * Class list for the element.
     */
    public string|array $class = [];

    /**
     * CSS styles for the element.
     */
    public string|array $css = [];

    /**
     * Additional attributes for the element.
     */
    public array $attributes = [];

    /**
     * Build attributes for the element.
     */
    public function buildAttributes(?Model $row = null): ComponentAttributeBag
    {
        $this->prepareAttributes($row);

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

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        //
    }
}
