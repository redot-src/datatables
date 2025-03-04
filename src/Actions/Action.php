<?php

namespace Redot\Datatables\Actions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use InvalidArgumentException;
use Redot\Datatables\Traits\BuildAttributes;

class Action
{
    use BuildAttributes;

    /**
     * The label of the action.
     */
    public ?string $label = null;

    /**
     * The icon of the action.
     */
    public ?string $icon = null;

    /**
     * The route of the action.
     */
    public ?string $route = null;

    /**
     * The route parameters of the action.
     */
    public array $parameters = [];

    /**
     * The method of the action.
     */
    public string $method = 'get';

    /**
     * Determine if the action is visible.
     */
    public bool $visible = true;

    /**
     * The condition callback of the action.
     */
    public ?Closure $condition = null;

    /**
     * Determine if the action should be opened in a new tab.
     */
    public bool $newTab = false;

    /**
     * Determine if the action should be opened in fancybox.
     */
    public bool $fancybox = false;

    /**
     * A flag to indicate that the class is not an action group.
     */
    final public $isActionGroup = false;

    /**
     * Create a new action instance.
     */
    public function __construct(?string $label = null, ?string $icon = null)
    {
        $this->label = $label;
        $this->icon = $icon;
    }

    /**
     * Create a new action instance.
     */
    public static function make(?string $label = null, ?string $icon = null): Action
    {
        return new static($label, $icon);
    }

    /**
     * Set the label of the action.
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon of the action.
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the route of the action.
     */
    public function route(string $route, array $parameters = [], ?string $method = null): self
    {
        $this->route = $route;
        $this->parameters = $parameters;

        if ($method) {
            $this->method($method);
        }

        return $this;
    }

    /**
     * Set the method of the action.
     */
    public function method(string $method): self
    {
        $allowedMethods = ['get', 'post', 'put', 'patch', 'delete'];
        $method = strtolower($method);

        if (! in_array($method, $allowedMethods)) {
            throw new InvalidArgumentException(sprintf('Invalid method provided "%s", allowed methods are: %s.', $method, implode(', ', $allowedMethods)));
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Set the visibility of the action.
     */
    public function visible(bool $visible = true): Action
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the visibility of the action to hidden.
     */
    public function hidden(bool $hidden = true): Action
    {
        return $this->visible(! $hidden);
    }

    /**
     * Set the condition callback of the action.
     */
    public function condition(Closure $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Set the action to be opened in a new tab.
     */
    public function newTab(bool $newTab = true): self
    {
        $this->newTab = $newTab;

        return $this;
    }

    /**
     * Set the action to be opened in fancybox.
     */
    public function fancybox(bool $fancybox = true): self
    {
        $this->fancybox = $fancybox;

        return $this;
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        if ($this->route) {
            $parameters = Request::route()->parameters();
            $parameters = array_merge($parameters, $this->parameters);

            $this->attributes['href'] = route($this->route, array_merge([$row], $parameters));
            $this->attributes['action-method'] = $this->method;
            $this->attributes['csrf-token'] = csrf_token();
        }

        if ($this->newTab) {
            $this->attributes['target'] = '_blank';
        }

        if ($this->fancybox) {
            $this->attributes['data-fancybox'] = '';
            $this->attributes['data-type'] = 'iframe';
        }
    }
}
