<?php

namespace Redot\Datatables\Actions;

use Redot\Datatables\Traits\BuildAttributes;

class ActionGroup
{
    use BuildAttributes;

    /**
     * The label of the action group.
     */
    public ?string $label = null;

    /**
     * The icon of the action group.
     */
    public ?string $icon = null;

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
     * Set the actions of the action group.
     */
    public function actions(array $actions): ActionGroup
    {
        $this->actions = $actions;

        return $this;
    }
}
