<?php

namespace Redot\LivewireDatatable;

use Closure;
use Exception;
use Illuminate\Support\Traits\Macroable;

class HeaderButton
{
    use Macroable;

    /**
     * Action callback.
     */
    public Closure $callback;

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
    public static function button(string $route = '', array|callable $routeParams = [], string $title = '', string $icon = '', array|callable $attrs = [], bool $fancybox = false): static
    {
        return static::make()
            ->do(function () use ($route, $routeParams, $title, $icon, $attrs, $fancybox) {
                $template = config('livewire-datatable.templates.header-button');

                if (is_callable($routeParams)) {
                    $routeParams = call_user_func($routeParams);
                }

                try {
                    $href = route($route, $routeParams ?: request()->route()->parameters());
                } catch (Exception) {
                    $href = $route;
                }

                if (is_callable($attrs)) {
                    $attrs = call_user_func($attrs);
                }

                if ($fancybox) {
                    $attrs['data-fancybox'] = uniqid('fancybox-');
                    $attrs['data-type'] = 'iframe';
                }

                return view($template, [
                    'href' => $href,
                    'title' => $title,
                    'icon' => $icon,
                    'attrs' => $attrs,
                ])->render();
            });
    }

    public static function create(string $route, array $routeParams = [], array|callable $attrs = [], bool $fancybox = false): static
    {
        return static::button(
            route: $route,
            routeParams: $routeParams,
            title: __('Create'),
            icon: config('livewire-datatable.icons.create'),
            attrs: fn () => array_merge(['datatable-action' => 'create'], is_callable($attrs) ? call_user_func($attrs) : $attrs),
            fancybox: $fancybox
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
    public function render(): string
    {
        return call_user_func($this->callback);
    }
}
