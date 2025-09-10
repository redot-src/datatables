<?php

namespace Redot\Datatables\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class CustomFilter extends Filter
{
    /**
     * The base filter class to inherit behavior from.
     */
    public string $base;

    /**
     * The callback to apply the filter to the query.
     */
    public Closure $callback;

    /**
     * Set the filter base.
     */
    public function base(string $base): self
    {
        if (! class_exists($base) || ! is_subclass_of($base, Filter::class)) {
            throw new InvalidArgumentException('The base must be a valid filter class.');
        }

        $this->base = $base;

        return $this;
    }

    /**
     * Set the callback to apply the filter to the query.
     */
    public function callback(Closure $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Render the filter view.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        if (! isset($this->base) || empty($this->base)) {
            throw new InvalidArgumentException('The base must be set.');
        }

        $base = new $this->base;

        return view($base->view, ['filter' => $this]);
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Builder $query, $value): void
    {
        // Early return if the callback is not set.
        if (! isset($this->callback)) {
            return;
        }

        // Apply the filter to the query.
        call_user_func($this->callback, $query, $value);
    }
}
