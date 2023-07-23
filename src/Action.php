<?php

namespace Redot\LivewireDatatable;

use Closure;
use Illuminate\Support\Facades\Gate;
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
     * Make new action.
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Make view action.
     */
    public static function view(string $route): static
    {
        return static::make()
            ->do(fn ($row) => view('livewire-datatable::actions.view', ['url' => route($route, $row)]));
    }

    /**
     * Make edit action.
     */
    public static function edit(string $route): static
    {
        return static::make()
            ->do(fn ($row) => view('livewire-datatable::actions.edit', ['url' => route($route, $row)]));
    }

    /**
     * Make delete action.
     */
    public static function delete(string $route): static
    {
        return static::make()
            ->do(fn ($row) => view('livewire-datatable::actions.delete', ['url' => route($route, $row)]));
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
     * Permission condition.
     */
    public function can(string $permission): static
    {
        return $this->when(fn () => Gate::allows($permission));
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
