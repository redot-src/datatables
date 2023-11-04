<?php

namespace Redot\LivewireDatatable;

use Closure;
use Illuminate\Support\Traits\Macroable;

class Action
{
    use Macroable;

    /**
     * Action callback.
     */
    public Closure $callback;

    /**
     * Action condition.
     */
    public ?Closure $condition = null;

    /**
     * Action allowed.
     */
    public bool $allowed = true;

    /**
     * Make new action.
     */
    public static function make(): static
    {
        return new static();
    }

    public static function button(string $route = '', string $title = '', string $icon = '', string $method = 'GET', array $attributes = []): static
    {
        return static::make()
            ->do(fn ($row) => view('livewire-datatable::action', [
                'url' => route($route, $row),
                'title' => $title,
                'icon' => $icon,
                'method' => $method,
                'attributes' => $attributes,
            ]));
    }

    /**
     * Make view action.
     */
    public static function view(string $route): static
    {
        return static::button(
            route: $route,
            title: __('View'),
            icon: config('livewire-datatable.icons.view'),
            method: 'GET',
            attributes: [
                'datatable-action' => 'view',
            ],
        );
    }

    /**
     * Make edit action.
     */
    public static function edit(string $route): static
    {
        return static::button(
            route: $route,
            title: __('Edit'),
            icon: config('livewire-datatable.icons.edit'),
            method: 'GET',
            attributes: [
                'datatable-action' => 'edit',
            ],
        );
    }

    /**
     * Make delete action.
     */
    public static function delete(string $route): static
    {
        return static::button(
            route: $route,
            title: __('Delete'),
            icon: config('livewire-datatable.icons.delete'),
            method: 'DELETE',
            attributes: [
                'datatable-action' => 'delete',
            ],
        );
    }

    /**
     * Set action callback.
     */
    public function do(Closure $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Action conditionable.
     */
    public function when(Closure $callback): static
    {
        $this->condition = $callback;

        return $this;
    }

    /**
     * Action allowed.
     */
    public function allowed(bool $allowed): static
    {
        $this->allowed = $allowed;

        return $this;
    }

    /**
     * Render the action.
     */
    public function render(mixed $row): string
    {
        if ($this->condition && ! call_user_func($this->condition, $row)) {
            return '';
        }

        return call_user_func($this->callback, $row);
    }
}
