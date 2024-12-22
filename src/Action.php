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
    public static function button(string $route = '', array|callable $routeParams = [], string $method = 'GET', string $title = '', string $icon = '', array|callable $attrs = [], bool $fancybox = false): static
    {
        return static::make()
            ->do(function ($row) use ($route, $routeParams, $method, $title, $icon, $attrs, $fancybox) {
                $template = config('livewire-datatable.templates.row-action');

                if (is_callable($routeParams)) {
                    $routeParams = call_user_func($routeParams, $row);
                }

                try {
                    $routeParams = $routeParams ?: request()->route()->parameters();
                    $href = route($route, array_merge($routeParams, [$row]));
                } catch (Exception) {
                    $href = $route;
                }

                if (is_callable($attrs)) {
                    $attrs = call_user_func($attrs, $row);
                }

                if ($fancybox) {
                    $attrs['data-fancybox'] = uniqid('fancybox-');
                    $attrs['data-type'] = 'iframe';
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
    public static function view(string $route, array|callable $routeParams = [], array|callable $attrs = [], bool $fancybox = false): static
    {
        return static::button(
            route: $route,
            routeParams: $routeParams,
            method: 'GET',
            title: __('View'),
            icon: config('livewire-datatable.icons.view'),
            attrs: fn ($row) => array_merge(['datatable-action' => 'view'], is_callable($attrs) ? call_user_func($attrs, $row) : $attrs),
            fancybox: $fancybox,
        );
    }

    /**
     * Make edit action.
     */
    public static function edit(string $route, array|callable $routeParams = [], array|callable $attrs = [], bool $fancybox = false): static
    {
        return static::button(
            route: $route,
            routeParams: $routeParams,
            method: 'GET',
            title: __('Edit'),
            icon: config('livewire-datatable.icons.edit'),
            attrs: fn ($row) => array_merge(['datatable-action' => 'edit'], is_callable($attrs) ? call_user_func($attrs, $row) : $attrs),
            fancybox: $fancybox,
        );
    }

    /**
     * Make delete action.
     */
    public static function delete(string $route, array|callable $routeParams = [], array|callable $attrs = []): static
    {
        return static::button(
            route: $route,
            routeParams: $routeParams,
            method: 'DELETE',
            title: __('Delete'),
            icon: config('livewire-datatable.icons.delete'),
            attrs: fn ($row) => array_merge(['datatable-action' => 'delete'], is_callable($attrs) ? call_user_func($attrs, $row) : $attrs),
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
