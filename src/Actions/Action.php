<?php

namespace Redot\Datatables\Actions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Redot\Datatables\Traits\BuildAttributes;

class Action
{
    use BuildAttributes;
    use Macroable;

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
     * The href of the action.
     */
    public string|Closure|null $href = null;

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
     * Determine if the action is grouped.
     */
    public bool $grouped = false;

    /**
     * Determine if the action is expanded.
     */
    public bool $expanded = false;

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
     * Determine if the action is confirmable.
     */
    public bool $confirmable = false;

    /**
     * Confirm message for the action.
     */
    public ?string $confirmMessage = null;

    /**
     * A flag to indicate that the class is not an action group.
     */
    public $isActionGroup = false;

    /**
     * Create a new action instance.
     */
    public function __construct(?string $label = null, ?string $icon = null)
    {
        if ($label) {
            $this->label($label);
        }

        if ($icon) {
            $this->icon($icon);
        }
    }

    /**
     * Create a new action instance.
     */
    public static function make(?string $label = null, ?string $icon = null): Action
    {
        return new static($label, $icon);
    }

    /**
     * Create a new view action instance.
     */
    public static function view(?string $route = null, array $parameters = []): Action
    {
        $action = static::make(__('datatables::datatable.actions.view'), 'fas fa-eye')->fancybox();

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Create a new edit action instance.
     */
    public static function edit(?string $route = null, array $parameters = []): Action
    {
        $action = static::make(__('datatables::datatable.actions.edit'), 'fas fa-edit');

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Create a new delete action instance.
     */
    public static function delete(?string $route = null, array $parameters = []): Action
    {
        $action = static::make(__('datatables::datatable.actions.delete'), 'fas fa-trash-alt')->method('delete')->confirmable();

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Create a new export action instance.
     */
    public static function export(?string $route = null, array $parameters = []): Action
    {
        $action = static::make(__('datatables::datatable.actions.export'), 'fas fa-file-export');

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
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
     * Set the href of the action.
     */
    public function href(string|Closure $href): self
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Set the parameters of the action.
     */
    public function parameters(array $parameters): self
    {
        $this->parameters = $parameters;

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
     * Set the action to be grouped.
     */
    public function grouped(bool $grouped = true): self
    {
        $this->grouped = $grouped;

        return $this;
    }

    /**
     * Set the action to be expanded.
     */
    public function expanded(bool $expanded = true): self
    {
        $this->expanded = $expanded;

        return $this;
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
     * Set the action to be confirmable.
     */
    public function confirmable(bool $confirmable = true, ?string $message = null): self
    {
        $this->confirmable = $confirmable;

        if ($message) {
            $this->confirmMessage($message);
        }

        return $this;
    }

    /**
     * Set the confirm message for the action.
     */
    public function confirmMessage(string $confirmMessage): self
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * Determine if the action should be rendered.
     */
    public function shouldRender(Model $row): bool
    {
        return $this->visible && ($this->condition ? call_user_func($this->condition, $row) : true);
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        if ($this->confirmable && $this->method === 'get') {
            throw new InvalidArgumentException('Confirmable actions must have a method other than "get".');
        }

        if ($this->route) {
            $parameters = $this->parameters;
            foreach ($parameters as $key => $value) {
                $parameters[$key] = $this->evaluate($value, $row);
            }

            $this->attributes([
                'href' => route($this->route, array_merge([$row], $parameters)),
                'method' => $this->method,
                'token' => csrf_token(),
            ]);
        }

        if ($this->href) {
            $this->attribute('href', $this->evaluate($this->href, $row));
        }

        if ($this->newTab) {
            $this->attribute('target', '_blank');
        }

        if ($this->fancybox) {
            $this->attributes([
                'data-fancybox' => '',
                'data-type' => 'iframe',
            ]);
        }

        if ($this->confirmable) {
            $this->attribute('confirm', $this->confirmMessage ?? __('datatables::datatable.actions.confirm'));
        }

        if ($this->label && ! $this->grouped && ! $this->expanded) {
            $this->attributes([
                'title' => $this->label,
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'bottom',
            ]);
        }

        // Append the class for the action
        $this->class('datatable-action');

        if ($this->grouped) {
            $this->class('dropdown-item');
        } else {
            $this->class('btn');
            $this->class(['btn-icon' => $this->icon && ! $this->expanded]);
        }
    }
}
