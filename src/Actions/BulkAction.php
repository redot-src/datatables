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
     * The label of the bulk action.
     */
    public ?string $label = null;

    /**
     * The icon of the bulk action.
     */
    public ?string $icon = null;

    /**
     * The route of the bulk action.
     */
    public ?string $route = null;

    /**
     * The href of the bulk action.
     */
    public string|Closure|null $href = null;

    /**
     * The route parameters of the bulk action.
     */
    public array $parameters = [];

    /**
     * Form data to be sent with the bulk action.
     */
    public array $body = [];

    /**
     * The method of the bulk action.
     */
    public string $method = 'post';

    /**
     * Determine if the bulk action is visible.
     */
    public bool $visible = true;

    /**
     * The condition callback of the bulk action.
     */
    public ?Closure $condition = null;

    /**
     * Determine if the bulk action should be opened in a new tab.
     */
    public bool $newTab = false;

    /**
     * Determine if the bulk action is confirmable.
     */
    public bool $confirmable = false;

    /**
     * Confirm message for the bulk action.
     */
    public ?string $confirmMessage = null;

    /**
     * Minimum number of selected items required.
     */
    public int $minSelection = 1;

    /**
     * Maximum number of selected items allowed.
     */
    public ?int $maxSelection = null;

    /**
     * Button variant/style class.
     */
    public string $variant = 'outline-primary';

    /**
     * Create a new bulk action instance.
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
     * Create a new bulk action instance.
     */
    public static function make(?string $label = null, ?string $icon = null): BulkAction
    {
        return new static($label, $icon);
    }

    /**
     * Create a new bulk delete action instance.
     */
    public static function delete(?string $route = null, array $parameters = []): BulkAction
    {
        $action = static::make(__('datatables::datatable.bulk_actions.delete'), 'fas fa-trash-alt')
            ->method('delete')
            ->variant('outline-danger')
            ->confirmable();

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Create a new bulk export action instance.
     */
    public static function export(?string $route = null, array $parameters = []): BulkAction
    {
        $action = static::make(__('datatables::datatable.bulk_actions.export'), 'fas fa-file-export')
            ->variant('outline-success');

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Create a new bulk update action instance.
     */
    public static function update(?string $route = null, array $parameters = []): BulkAction
    {
        $action = static::make(__('datatables::datatable.bulk_actions.update'), 'fas fa-edit')
            ->method('patch')
            ->variant('outline-warning');

        if ($route) {
            $action->route($route, $parameters);
        }

        return $action;
    }

    /**
     * Set the label of the bulk action.
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the icon of the bulk action.
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the route of the bulk action.
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
     * Set the href of the bulk action.
     */
    public function href(string|Closure $href): self
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Set the parameters of the bulk action.
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
     * Set the method of the bulk action.
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
     * Set the visibility of the bulk action.
     */
    public function visible(bool $visible = true): BulkAction
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the visibility of the bulk action to hidden.
     */
    public function hidden(bool $hidden = true): BulkAction
    {
        return $this->visible(! $hidden);
    }

    /**
     * Set the condition callback of the bulk action.
     */
    public function condition(Closure $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Set the bulk action to be opened in a new tab.
     */
    public function newTab(bool $newTab = true): self
    {
        $this->newTab = $newTab;

        return $this;
    }

    /**
     * Set the bulk action to be confirmable.
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
     * Set the confirm message for the bulk action.
     */
    public function confirmMessage(string $confirmMessage): self
    {
        $this->confirmMessage = $confirmMessage;

        return $this;
    }

    /**
     * Set the minimum selection required.
     */
    public function minSelection(int $minSelection): self
    {
        $this->minSelection = $minSelection;

        return $this;
    }

    /**
     * Set the maximum selection allowed.
     */
    public function maxSelection(?int $maxSelection): self
    {
        $this->maxSelection = $maxSelection;

        return $this;
    }

    /**
     * Set the button variant.
     */
    public function variant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Determine if the bulk action should be rendered.
     */
    public function shouldRender(): bool
    {
        return $this->visible && ($this->condition ? call_user_func($this->condition) : true);
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(): void
    {
        if ($this->confirmable && $this->method === 'get') {
            throw new InvalidArgumentException('Confirmable bulk actions must have a method other than "get".');
        }

        if ($this->route) {
            $parameters = $this->parameters;

            $body = $this->body;

            // If the method is GET, we need to append the body to query string
            if ($this->method === 'get') {
                $parameters = array_merge($parameters, $body);
            } else {
                $this->attribute('request-body', base64_encode(json_encode($body, JSON_UNESCAPED_UNICODE)));
            }

            $this->attributes([
                'href' => route($this->route, $parameters),
                'method' => $this->method,
                'token' => csrf_token(),
            ]);
        }

        if ($this->href) {
            $this->attribute('href', is_callable($this->href) ? call_user_func($this->href) : $this->href);
        }

        if ($this->newTab) {
            $this->attribute('target', '_blank');
        }

        if ($this->confirmable) {
            $this->attribute('confirm', $this->confirmMessage ?? __('datatables::datatable.bulk_actions.confirm'));
        }

        if ($this->minSelection > 1) {
            $this->attribute('min-selection', $this->minSelection);
        }

        if ($this->maxSelection) {
            $this->attribute('max-selection', $this->maxSelection);
        }

        $this->attributes([
            'title' => $this->label,
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
        ]);

        // Append the class for the bulk action
        $this->class(['datatable-bulk-action', 'btn', 'btn-' . $this->variant]);
    }
} 