<?php

namespace Redot\Datatables\Actions;

use Closure;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Redot\Datatables\Traits\BuildAttributes;

class BulkAction
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
     * The route parameters of the action.
     */
    public array $parameters = [];

    /**
     * Form data to be sent with the action.
     * For bulk actions, this might include the list of selected IDs.
     */
    public array $body = [];

    /**
     * The method of the action.
     */
    public string $method = 'post'; // Default to POST for bulk actions

    /**
     * Determine if the action is visible.
     */
    public bool $visible = true;

    /**
     * The condition callback of the action.
     * For bulk actions, this might depend on the selected items.
     */
    public ?Closure $condition = null;

    /**
     * Determine if the action should be opened in a new tab.
     */
    public bool $newTab = false;

    /**
     * Determine if the action is confirmable.
     */
    public bool $confirmable = false;

    /**
     * Confirm message for the action.
     */
    public ?string $confirmMessage = null;

    /**
     * The name of the action, used to identify it.
     */
    public string $name;

    /**
     * Create a new action instance.
     */
    public function __construct(string $name, ?string $label = null, ?string $icon = null)
    {
        $this->name = $name;

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
    public static function make(string $name, ?string $label = null, ?string $icon = null): static
    {
        return new static($name, $label, $icon);
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
        $this->parameters = array_merge($this->parameters, $parameters);

        if ($method) {
            $this->method($method);
        }

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
     * Set the body of the form request.
     */
    public function body(array $body = []): self
    {
        $this->body = array_merge($this->body, $body);

        return $this;
    }

    /**
     * Set the method of the action.
     */
    public function method(string $method): self
    {
        $allowedMethods = ['get', 'post', 'put', 'patch', 'delete'];
        $method = strtolower($method);

        if (!in_array($method, $allowedMethods)) {
            throw new InvalidArgumentException(sprintf('Invalid method provided "%s", allowed methods are: %s.', $method, implode(', ', $allowedMethods)));
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Set the visibility of the action.
     */
    public function visible(bool $visible = true): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the visibility of the action to hidden.
     */
    public function hidden(bool $hidden = true): self
    {
        return $this->visible(!$hidden);
    }

    /**
     * Set the condition callback of the action.
     * The callback will receive the array of selected row IDs.
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
     * Set the action to be confirmable.
     */
    public function confirmable(bool $confirmable = true, ?string $message = null): self
    {
        $this->confirmable = $confirmable;

        if ($message) {
            $this->confirmMessage($message);
        } else {
            $this->confirmMessage = __('datatables::datatable.actions.bulk_confirm');
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
     * @param array $selectedRowIds The IDs of the selected rows.
     */
    public function shouldRender(array $selectedRowIds): bool
    {
        if (!$this->visible) {
            return false;
        }

        if ($this->condition) {
            return call_user_func($this->condition, $selectedRowIds);
        }

        return true;
    }

    /**
     * Prepare the attributes before building.
     * @param array $selectedRowIds The IDs of the selected rows.
     */
    protected function prepareAttributes(array $selectedRowIds = []): void
    {
        // Reset attributes for each build
        $this->attributes = [];

        if ($this->confirmable && $this->method === 'get') {
            // Consider implications for GET requests that are confirmable
        }

        $url = '#';
        if ($this->route) {
            $routeParameters = $this->parameters;
            $url = route($this->route, $routeParameters);
        }

        $this->attributes([
            'data-url' => $url,
            'data-method' => $this->method,
        ]);

        if ($this->newTab) {
            $this->attribute('target', '_blank');
        }

        if ($this->confirmable) {
            $this->attribute('data-confirm', $this->confirmMessage ?? __('datatables::datatable.actions.bulk_confirm_default'));
        }

        $this->class('datatable-bulk-action');
        $this->class('btn'); // Example: style as a button
    }

    /**
     * Get the prepared attributes for rendering the action button/link.
     * @param array $selectedRowIds The IDs of the selected rows.
     * @return string
     */
    public function buildActionAttributes(array $selectedRowIds = []): string
    {
        $this->prepareAttributes($selectedRowIds);
        return $this->renderAttributes();
    }
}
