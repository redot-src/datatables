<?php

namespace Redot\Datatables\Contracts;

interface ActionGroup
{
    /**
     * Set the label of the action group.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): ActionGroup;

    /**
     * Set the icon of the action group.
     *
     * @param string $icon
     * @return $this
     */
    public function icon(string $icon): ActionGroup;

    /**
     * Set the action group's class.
     *
     * @param string $class
     * @return $this
     */
    public function class(string $class): ActionGroup;

    /**
     * Set the action group's css.
     *
     * @param array $css
     * @return $this
     */
    public function css(array $css): ActionGroup;

    /**
     * Set the action group's attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes): ActionGroup;

    /**
     * Set the actions of the action group.
     *
     * @param Action[] $actions
     * @return $this
     */
    public function actions(array $actions): ActionGroup;
}
