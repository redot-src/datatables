<?php

namespace Redot\Datatables\Actions;

class ActionGroup
{
    /**
     * The label of the action group.
     */
    public ?string $label = null;

    /**
     * The icon of the action group.
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
     * The attributes of the action group.
     */
    public array $attributes = [];

    /**
     * The actions of the action group.
     */
    public array $actions = [];

    /**
     * Create a new action group instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new action group instance.
     */
    public static function make(): ActionGroup
    {
        return new static;
    }

    /**
     * Set the label of the action group.
     */
    public function label(string $label): ActionGroup
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon of the action group.
     */
    public function icon(string $icon): ActionGroup
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the action group's class.
     */
    public function class(string|array $class): ActionGroup
    {
        $this->class = is_array($class) ? implode(' ', $class) : $class;

        return $this;
    }

    /**
     * Set the action group's css.
     */
    public function css(string|array $css): ActionGroup
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Set the action group's attributes.
     */
    public function attributes(array $attributes): ActionGroup
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set the actions of the action group.
     */
    public function actions(array $actions): ActionGroup
    {
        $this->actions = $actions;

        return $this;
    }
}
