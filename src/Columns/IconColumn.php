<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class IconColumn extends Column
{
    /**
     * Determine if the column content is HTML.
     */
    public bool $html = true;

    /**
     * Determine if the column is exportable.
     */
    public bool $exportable = false;

    /**
     * Class list for the element.
     */
    public array $class = ['text-center'];

    /**
     * The column's width.
     */
    public string $width = '50px';

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return sprintf('<i class="%s"></i>', $value);
    }
}
