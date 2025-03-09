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
    public array $class = [];

    /**
     * CSS styles for the element.
     */
    public array $css = [];

    /**
     * Additional attributes for the element.
     */
    public array $attributes = [];

    /**
     * Set the class list for the element.
     */
    public function class($class): static
    {
        $this->class = array_merge($this->class, Arr::wrap($class));

        return $this;
    }

    /**
     * Set the CSS styles for the element.
     */
    public function css($css): static
    {
        $this->css = array_merge($this->css, Arr::wrap($css));

        return $this;
    }

    /**
     * Set additional attributes for the element.
     */
    public function attributes(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Add attribute to the element.
     */
    public function attribute(string $key, $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Clone the element.
     */
    protected function clone(): static
    {
        return clone $this;
    }

    /**
     * Build attributes for the element.
     */
    public function buildAttributes(?Model $row = null): ComponentAttributeBag
    {
        $clone = clone $this;
        $clone->prepareAttributes($row);

        $attributes = [];
        foreach ($clone->attributes as $key => $value) {
            $attributes[$key] = $this->evaluate($value, $row);
        }

        $classes = [];
        foreach ($clone->class as $key => $value) {
            $classes[$key] = $this->evaluate($value, $row);
        }

        $styles = [];
        foreach ($clone->css as $key => $value) {
            $styles[$key] = $this->evaluate($value, $row);
        }

        // Clean up the clone from memory
        unset($clone);

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

    /**
     * Evaluate the value.
     */
    protected function evaluate(mixed $value, ...$args)
    {
        // Early return if the value is a string
        if (is_string($value)) {
            return $value;
        }

        return is_callable($value) ? call_user_func($value, ...$args) : $value;
    }
}
