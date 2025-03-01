<?php

namespace Redot\Datatables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Illuminate\View\ComponentAttributeBag;
use Redot\Datatables\Contracts\Column as ColumnContract;

class Column implements ColumnContract
{
    use Macroable;

    /**
     * The column's type.
     *
     * @var string
     */
    public string|null $type = null;

    /**
     * The column's name.
     *
     * @var string
     */
    public string|null $name = null;

    /**
     * Determine if the column is a relationship.
     *
     * @var bool
     */
    public bool $relationship = false;

    /**
     * The column's label.
     *
     * @var string
     */
    public string|null $label = null;

    /**
     * The column's empty value if null.
     *
     * @var string|Closure
     */
    public string|Closure $empty = '-';

    /**
     * The column label class.
     *
     * @var string
     */
    public string $class = '';

    /**
     * The column css styles.
     *
     * @var array<string, string>
     */
    public array $css = [];

    /**
     * The column html attributes.
     *
     * @var array<string, string>
     */
    public array $attributes = [];

    /**
     * The column's width.
     *
     * @var string
     */
    public string $width = 'auto';

    /**
     * Determine if the column is a fixed column.
     *
     * @var bool
     */
    public bool $fixed = false;

    /**
     * Determine if the column content is HTML.
     *
     * @var bool
     */
    public bool $html = false;

    /**
     * The column's default value.
     *
     * @var mixed
     */
    public mixed $default = null;

    /**
     * Determine if the column is sortable.
     *
     * @var bool
     */
    public bool $sortable = false;

    /**
     * The sorting method for the column.
     *
     * @var Closure|null
     */
    public Closure|null $sorter = null;

    /**
     * Determine if the column is searchable.
     *
     * @var bool
     */
    public bool $searchable = false;

    /**
     * The searching method for the column.
     *
     * @var Closure|null
     */
    public Closure|null $searcher = null;

    /**
     * Determine if the column is visible.
     *
     * @var bool
     */
    public bool $visible = true;

    /**
     * Determine if the column is editable.
     *
     * @var bool
     */
    public bool $editable = false;

    /**
     * Determine if the column is exportable.
     *
     * @var bool
     */
    public bool $exportable = true;

    /**
     * The getter method for the column.
     *
     * @var Closure|null
     */
    public Closure|null $getter = null;

    /**
     * The setter method for the column.
     *
     * @var Closure|null
     */
    public Closure|null $setter = null;

    /**
     * Create a new column instance.
     *
     * @param string|null $name
     * @param string|null $label
     */
    public function __construct(string|null $name = null, string|null $label = null)
    {
        if ($name) {
            $this->name($name);
        }

        if ($label) {
            $this->label($label);
        }
    }

    /**
     * Make a new column instance.
     *
     * @param string|null $name
     * @param string|null $label
     * @return static
     */
    public static function make(string|null $name = null, string|null $label = null): Column
    {
        return new static($name, $label);
    }

    /**
     * Set the column's type.
     *
     * @param string $type
     * @return $this
     */
    public function type(string $type): Column
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the column's name.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): Column
    {
        $this->name = $name;
        $this->relationship = str_contains($name, '.');

        return $this;
    }

    /**
     * Set the column's label.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): Column
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the column's empty value if null.
     *
     * @param string $empty
     * @return $this
     */
    public function empty(string|Closure $empty): Column
    {
        $this->empty = $empty;

        return $this;
    }

    /**
     * Set the column's content class.
     *
     * @param string|array $class
     * @return $this
     */
    public function class(string|array $class): Column
    {
        $this->class = is_array($class) ? implode(' ', $class) : $class;

        return $this;
    }

    /**
     * Set the column's content css styles.
     *
     * @param string|array $css
     * @return $this
     */
    public function css(string|array $css): Column
    {
        $css = Arr::wrap($css);

        if (Arr::isAssoc($css)) {
            $css = collect($css)->map(fn ($value, $key) => "$key: $value")->values()->toArray();
        }

        $this->css = $css;

        return $this;
    }

    /**
     * Set the column's content html attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes): Column
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set the column's width.
     *
     * @param string $width
     * @return $this
     */
    public function width(string $width): Column
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the column as fixed.
     *
     * @param bool $fixed
     * @return $this
     */
    public function fixed(bool $fixed = true): Column
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Set the column as HTML.
     *
     * @param bool $html
     * @return $this
     */
    public function html(bool $html = true): Column
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Set the column's default value.
     *
     * @param mixed $default
     * @return $this
     */
    public function default(mixed $default): Column
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Set the column as sortable.
     *
     * @param bool $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true): Column
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Set the sorting method for the column.
     *
     * @param Closure $sorter
     * @return $this
     */
    public function sorter(Closure $sorter): Column
    {
        $this->sorter = $sorter;
        $this->sortable = true;

        return $this;
    }

    /**
     * Set the column as searchable.
     *
     * @param bool $searchable
     * @return $this
     */
    public function searchable(bool $searchable = true): Column
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Set the searching method for the column.
     *
     * @param Closure $searcher
     * @return $this
     */
    public function searcher(Closure $searcher): Column
    {
        $this->searcher = $searcher;
        $this->searchable = true;

        return $this;
    }

    /**
     * Set the column as visible.
     *
     * @param bool $visible
     * @return $this
     */
    public function visible(bool $visible = true): Column
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Set the column as hidden.
     *
     * @param bool $hidden
     * @return $this
     */
    public function hidden(bool $hidden = true): Column
    {
        $this->visible = ! $hidden;

        return $this;
    }

    /**
     * Set the column as editable.
     *
     * @param bool $editable
     * @return $this
     */
    public function editable(bool $editable = true): Column
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Set the column as exportable.
     *
     * @param bool $exportable
     * @return $this
     */
    public function exportable(bool $exportable = true): Column
    {
        $this->exportable = $exportable;

        return $this;
    }

    /**
     * Set the getter method for the column.
     *
     * @param Closure $getter
     * @return $this
     */
    public function getter(Closure $getter): Column
    {
        $this->getter = $getter;

        return $this;
    }

    /**
     * Set the setter method for the column.
     *
     * @param Closure $setter
     * @return $this
     */
    public function setter(Closure $setter): Column
    {
        $this->setter = $setter;

        return $this;
    }

    /**
     * Get the value of the column.
     *
     * @param Model $row
     * @return mixed
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
     *
     * @param mixed $value
     * @param Model $row
     * @return mixed
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        return $value;
    }

    /**
     * Set the value of the column on the model [if editable].
     *
     * @param Model $row
     * @param mixed $value
     * @return void
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
     *
     * @param mixed $value
     * @param Model $row
     * @return void
     */
    protected function defaultSetter(mixed $value, Model $row): void
    {
        data_set($row, $this->name, $value);

        $row->save();
    }

    /**
     * Build attributes for the column.
     *
     * @param Model $row
     * @return ComponentAttributeBag
     */
    public function buildAttributes(Model $row): ComponentAttributeBag
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            $attributes[$key] = is_callable($value) ? $value($row) : $value;
        }

        return new ComponentAttributeBag($attributes);
    }
}
