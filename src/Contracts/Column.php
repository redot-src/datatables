<?php

namespace Redot\Datatables\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;

interface Column
{
    /**
     * Make a new column instance.
     *
     * @param string|null $name
     * @param string|null $label
     * @return static
     */
    public static function make(string $name = null, string $label = null): Column;

    /**
     * Set the column's name.
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): Column;

    /**
     * Set the column's label.
     *
     * @param string $label
     * @return $this
     */
    public function label(string $label): Column;

    /**
     * Set the column's label class.
     *
     * @param string $class
     * @return $this
     */
    public function class(string $class): Column;

    /**
     * Set the column's width.
     *
     * @param string $width
     * @return $this
     */
    public function width(string $width): Column;

    /**
     * Set the column as fixed.
     *
     * @param bool $fixed
     * @return $this
     */
    public function fixed(bool $fixed = true): Column;

    /**
     * Set the column as HTML.
     *
     * @param bool $html
     * @return $this
     */
    public function html(bool $html = true): Column;

    /**
     * Set the column's default value.
     *
     * @param mixed $default
     * @return $this
     */
    public function default(mixed $default): Column;

    /**
     * Set the column as sortable.
     *
     * @param bool $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true): Column;

    /**
     * Set the column as searchable.
     *
     * @param bool $searchable
     * @return $this
     */
    public function searchable(bool $searchable = true): Column;

    /**
     * Set the searching method for the column.
     *
     * @param Closure $searcher
     * @return $this
     */
    public function searcher(Closure $searcher): Column;

    /**
     * Set the column as visible.
     *
     * @param bool $visible
     * @return $this
     */
    public function visible(bool $visible = true): Column;

    /**
     * Set the column as hidden.
     *
     * @param bool $hidden
     * @return $this
     */
    public function hidden(bool $hidden = true): Column;

    /**
     * Set the column as editable.
     *
     * @param bool $editable
     * @return $this
     */
    public function editable(bool $editable = true): Column;

    /**
     * Set the column as exportable.
     *
     * @param bool $exportable
     * @return $this
     */
    public function exportable(bool $exportable = true): Column;

    /**
     * Get the value of the column.
     *
     * @param Model $row
     * @return mixed
     */
    public function get(Model $row): mixed;

    /**
     * Set the value of the column on the model [if editable].
     *
     * @param Model $row
     * @param mixed $value
     * @return void
     */
    public function set(Model $row, mixed $value): void;
}
