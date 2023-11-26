<?php

namespace Redot\LivewireDatatable;

use Closure;
use Exception;
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

    /**
     * Make button action.
     */
    public static function button(string $route = '', string $method = 'GET', string $title = '', string $icon = '', array $attrs = []): static
    {
        return static::make()
            ->do(function ($row) use ($route, $method, $title, $icon, $attrs) {
                $template = config('livewire-datatable.templates.row-action');

                try {
                    $href = route($route, array_merge(request()->route()->parameters(), [$row]));
                } catch (Exception) {
                    $href = $route;
                }

                return view($template, [
                    'href' => $href,
                    'method' => $method,
                    'title' => $title,
                    'icon' => $icon,
                    'attrs' => $attrs,
                ])->render();
            });
    }

    /**
     * Make view action.
     */
    public static function view(string $route): static
    {
        return static::button(
            route: $route,
            method: 'GET',
            title: __('View'),
            icon: config('livewire-datatable.icons.view'),
            attrs: ['datatable-action' => 'view'],
        );
    }

    /**
     * Make edit action.
     */
    public static function edit(string $route): static
    {
        return static::button(
            route: $route,
            method: 'GET',
            title: __('Edit'),
            icon: config('livewire-datatable.icons.edit'),
            attrs: ['datatable-action' => 'edit'],
        );
    }

    /**
     * Make delete action.
     */
    public static function delete(string $route): static
    {
        return static::button(
            route: $route,
            method: 'DELETE',
            title: __('Delete'),
            icon: config('livewire-datatable.icons.delete'),
            attrs: ['datatable-action' => 'delete'],
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
