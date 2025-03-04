<?php

namespace Redot\Datatables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Redot\Datatables\Traits\BuildAttributes;

class Column
{
    use BuildAttributes;
    use Macroable;

    /**
     * The column's type.
     */
    public ?string $type = null;

    /**
     * The column's name.
     */
    public ?string $name = null;

    /**
     * Determine if the column is a relationship.
     */
    public bool $relationship = false;

    /**
     * The column's label.
     */
    public ?string $label = null;

    /**
     * The column's empty value if null.
     */
    public string|Closure $empty = '-';

    /**
     * The column's width.
     */
    public string $width = 'auto';

    /**
     * The column's max width.
     */
    public ?string $maxWidth = null;

    /**
     * The column's min width.
     */
    public ?string $minWidth = null;

    /**
     * Determine if the column is a fixed column.
     */
    public bool $fixed = false;

    /**
     * The fixed direction of the column.
     */
    public string $fixedDirection = 'start';

    /**
     * Determine if the column whitespace should be nowrap.
     */
    public bool $nowrap = false;

    /**
     * Determine if the column content is HTML.
     */
    public bool $html = false;

    /**
     * The column's default value.
     */
    public mixed $default = null;

    /**
     * Determine if the column is sortable.
     */
    public bool $sortable = false;

    /**
     * The sorting method for the column.
     */
    public ?Closure $sorter = null;

    /**
     * Determine if the column is searchable.
     */
    public bool $searchable = false;

    /**
     * The searching method for the column.
     */
    public ?Closure $searcher = null;

    /**
     * Determine if the column is visible.
     */
    public bool $visible = true;

    /**
     * Determine if the column is editable.
     */
    public bool $editable = false;

    /**
     * Determine if the column is exportable.
     */
    public bool $exportable = true;

    /**
     * The getter method for the column.
     */
    public ?Closure $getter = null;

    /**
     * The setter method for the column.
     */
    public ?Closure $setter = null;

    /**
     * Create a new column instance.
     */
    public function __construct(?string $name = null, ?string $label = null)
    {
        if ($name) {
            $this->name($name);
        }

        if ($label) {
            $this->label($label);
        }

        $this->init();
    }

    /**
     * Make a new column instance.
     */
    public static function make(?string $name = null, ?string $label = null): Column
    {
        return new static($name, $label);
    }

    /**
     * Initialize the column.
     */
    protected function init(): void
    {
        //
    }

    /**
     * Set the column's type.
     */
    public function type(string $type): Column
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the column's name.
     */
    public function name(string $name): Column
    {
        $this->name = $name;
        $this->relationship = str_contains($name, '.');

        return $this;
    }

    /**
     * Set the column's label.
     */
    public function label(string $label): Column
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the column's empty value if null.
     */
    public function empty(string|Closure $empty): Column
    {
        $this->empty = $empty;

        return $this;
    }

    /**
     * Set the column's width.
     */
    public function width(string $width): Column
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the column's max width.
     */
    public function maxWidth(string $maxWidth): Column
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Set the column's min width.
     */
    public function minWidth(string $minWidth): Column
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    /**
     * Set the column as fixed.
     */
    public function fixed(bool $fixed = true, string $direction = 'start'): Column
    {
        $this->fixed = $fixed;
        $this->fixedDirection = $direction;

        return $this;
    }

    /**
     * Set the column's whitespace as nowrap.
     */
    public function nowrap(bool $nowrap = true): Column
    {
        $this->nowrap = $nowrap;

        return $this;
    }

    /**
     * Set the column as HTML.
     */
    public function html(bool $html = true): Column
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Set the column's default value.
     */
    public function default(mixed $default): Column
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Set the column as sortable.
     */
    public function sortable(bool $sortable = true): Column
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Set the sorting method for the column.
     */
    public function sorter(Closure $sorter): Column
    {
        $this->sorter = $sorter;
        $this->sortable = true;

        return $this;
    }

    /**
     * Set the column as searchable.
     */
    public function searchable(bool $searchable = true): Column
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Set the searching method for the column.
     */
    public function searcher(Closure $searcher): Column
    {
        $this->searcher = $searcher;
        $this->searchable = true;

        return $this;
    }

    /**
     * Set the column as visible.
     */
    public function visible(bool $visible = true): Column
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the column as hidden.
     */
    public function hidden(bool $hidden = true): Column
    {
        $this->visible = ! $hidden;

        return $this;
    }

    /**
     * Set the column as editable.
     */
    public function editable(bool $editable = true): Column
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Set the column as exportable.
     */
    public function exportable(bool $exportable = true): Column
    {
        $this->exportable = $exportable;

        return $this;
    }

    /**
     * Set the getter method for the column.
     */
    public function getter(Closure $getter): Column
    {
        $this->getter = $getter;

        return $this;
    }

    /**
     * Set the setter method for the column.
     */
    public function setter(Closure $setter): Column
    {
        $this->setter = $setter;

        return $this;
    }

    /**
     * Get the value of the column.
     */
    public function get(Model $row): mixed
    {
        $value = $this->name ? data_get($row, $this->name) : $this->default;
        $value = $this->defaultGetter($value, $row);

        if ($this->getter) {
            $value = call_user_func($this->getter, $value, $row);
        }

        if (is_null($value)) {
            $value = is_callable($this->empty) ? call_user_func($this->empty, $row) : $this->empty;
        }

        return $value;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        return $value;
    }

    /**
     * Set the value of the column on the model [if editable].
     */
    public function set(Model $row, mixed $value): void
    {
        if (! $this->editable) {
            return;
        }

        if ($this->setter) {
            call_user_func($this->setter, $value, $row);

            return;
        }

        $this->defaultSetter($value, $row);
    }

    /**
     * Default setter for the column.
     */
    protected function defaultSetter(mixed $value, Model $row): void
    {
        data_set($row, $this->name, $value);

        $row->save();
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        // Add fixed to the classes if the column is fixed.
        if ($this->fixed) {
            array_push($this->class, 'fixed-' . $this->fixedDirection);
        }

        // Add the column's width to the styles.
        $this->css = array_merge($this->css, [
            'width: ' . $this->width,
            'min-width: ' . ($this->minWidth ?? $this->width),
            'max-width: ' . ($this->maxWidth ?? $this->width),
        ]);

        // Add nowrap to the styles if the column is nowrap.
        if ($this->nowrap) {
            $this->css = array_merge($this->css, [
                'white-space: nowrap',
                'overflow: hidden',
                'text-overflow: ellipsis',
            ]);
        }

        // Add d-none to the classes if the column is not visible.
        if (! $this->visible) {
            array_push($this->class, 'd-none');
        }
    }
}
