<?php

namespace Redot\Datatables\Actions;

class Action
{
    /**
     * The label of the action.
     */
    public ?string $label = null;

    /**
     * The icon of the action.
     */
    public ?string $icon = null;

    /**
     * The action class.
     */
    public string|array $class = [];

    /**
     * The action css styles.
     */
    public string|array $css = [];

    /**
     * The attributes of the action.
     */
    public array $attributes = [];

    /**
     * Create a new action instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new action instance.
     */
    public static function make(): Action
    {
        return new static;
    }

    /**
     * Set the label of the action.
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon of the action.
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the class of the action.
     */
    public function class(string|array $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Set the css of the action.
     */
    public function css(string|array $css): self
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Set the attributes of the action.
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
