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
    public static function button(string $route = '', string $title = '', string $icon = '', string $class = 'btn', array $attrs = []): static
    {
        return static::make()
            ->do(function () use ($route, $title, $icon, $class, $attrs) {
                $template = config('livewire-datatable.templates.header-button');

                try {
                    $href = route($route, array_merge(request()->route()->parameters()));
                } catch (Exception) {
                    $href = $route;
                }

                return view($template, [
                    'href' => $href,
                    'title' => $title,
                    'icon' => $icon,
                    'class' => $class,
                    'attrs' => $attrs,
                ])->render();
            });
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
