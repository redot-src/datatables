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
    public function __construct(?string $label = null, ?string $icon = null)
    {
        $this->label = $label;
        $this->icon = $icon;
    }

    /**
     * Create a new action instance.
     */
    public static function make(?string $label = null, ?string $icon = null): Action
    {
        return new static($label, $icon);
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
