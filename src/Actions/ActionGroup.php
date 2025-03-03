<?php

namespace Redot\Datatables\Actions;

class ActionGroup
{
    /**
     * The label of the action group.
     *
     * @var string
     */
    public string|null $label = null;

    /**
     * The icon of the action group.
     *
     * @var string|null
     */
    public string|null $icon = null;

    /**
     * The class of the action group.
     *
     * @var string|null
     */
    public string|null $class = null;

    /**
     * The css of the action group.
     *
     * @var array<string, string>
     */
    public array $css = [];

    /**
     * The attributes of the action group.
     *
     * @var array<string, string>
     */
    public array $attributes = [];

    /**
     * The actions of the action group.
     *
     * @var Action[]
     */
    public array $actions = [];

    /**
     * Set the label of the action group.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): ActionGroup
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon of the action group.
     *
     * @param string $icon
     * @return $this
     */
    public function icon(string $icon): ActionGroup
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the action group's class.
     *
     * @param string|array $class
     * @return $this
     */
    public function class(string|array $class): ActionGroup
    {
        $this->class = is_array($class) ? implode(' ', $class) : $class;

        return $this;
    }

    /**
     * Set the action group's css.
     *
     * @param array<string, string> $css
     * @return $this
     */
    public function css(array $css): ActionGroup
    {
        $this->css = $css;

        return $this;
    }

    /**
     * Set the action group's attributes.
     *
     * @param array<string, string> $attributes
     * @return $this
     */
    public function attributes(array $attributes): ActionGroup
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set the actions of the action group.
     *
     * @param Action[] $actions
     * @return $this
     */
    public function actions(array $actions): ActionGroup
    {
        $this->actions = $actions;

        return $this;
    }
}
