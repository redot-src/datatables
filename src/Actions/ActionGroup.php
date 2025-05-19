<?php

namespace Redot\Datatables\Actions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Redot\Datatables\Traits\BuildAttributes;

class ActionGroup
{
    use BuildAttributes;
    use Macroable;

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
     * The condition callback of the action.
     */
    public ?Closure $condition = null;

    /**
     * A flag to indicate that the class is an action group.
     */
    public $isActionGroup = true;

    /**
     * Create a new action group instance.
     */
    public function __construct(?string $label = null, ?string $icon = null)
    {
        if ($label) {
            $this->label($label);
        }

        if ($icon) {
            $this->icon($icon);
        }
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

        foreach ($this->actions as $action) {
            $action->grouped(true);
        }

        return $this;
    }

    /**
     * Add an action to the action group.
     */
    public function add(Action $action): ActionGroup
    {
        $this->actions[] = $action->grouped(true);

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
     * Set the condition callback of the action group.
     */
    public function condition(Closure $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Determine if the action group should be rendered.
     */
    public function shouldRender(Model $row): bool
    {
        $hasChildren = ! empty(array_filter($this->actions, fn (Action $action) => $action->shouldRender($row)));

        return $this->visible && ($this->condition ? call_user_func($this->condition, $row) : true) && $hasChildren;
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        $this->class([
            'btn',
            'dropdown-toggle' => $this->label,
            'btn-icon' => $this->icon && ! $this->label,
        ]);

        // Append the dropdown attributes.
        $this->attributes([
            'data-bs-toggle' => 'dropdown',
            'wire:key' => sprintf('action-group-for-%s', $row->getKey()),
        ]);
    }
}
