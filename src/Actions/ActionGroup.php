<?php

namespace Redot\Datatables\Actions;

use Illuminate\Database\Eloquent\Model;
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
     * Determine if the action group is visible.
     */
    public bool $visible = true;

    /**
     * A flag to indicate that the class is an action group.
     */
    final public $isActionGroup = true;

    /**
     * Create a new action group instance.
     */
    public function __construct(?string $label = null, ?string $icon = null)
    {
        $this->label = $label;
        $this->icon = $icon;
    }

    /**
     * Create a new action group instance.
     */
    public static function make(?string $label = null, ?string $icon = null): ActionGroup
    {
        return new static($label, $icon);
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

    /**
     * Add an action to the action group.
     */
    public function addAction(Action $action): ActionGroup
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Set the visibility of the action group.
     */
    public function visible(bool $visible = true): ActionGroup
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the visibility of the action group to hidden.
     */
    public function hidden(bool $hidden = true): ActionGroup
    {
        return $this->visible(! $hidden);
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        $this->class = array_merge($this->class, [
            'btn',
            'dropdown-toggle' => $this->label,
            'btn-icon' => $this->icon && ! $this->label,
        ]);

        // Append the dropdown attributes.
        $this->attributes['data-bs-toggle'] = 'dropdown';
        $this->attributes['aria-expanded'] = 'false';
    }
}
