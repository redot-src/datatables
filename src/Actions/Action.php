<?php

namespace Redot\Datatables\Actions;

use Redot\Datatables\Traits\BuildAttributes;

class Action
{
    use BuildAttributes;

    /**
     * The label of the action.
     */
    public ?string $label = null;

    /**
     * The icon of the action.
     */
    public ?string $icon = null;

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
}
