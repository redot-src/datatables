<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class NumericColumn extends Column
{
    /**
     * The column's precision.
     */
    public ?int $precision = null;

    /**
     * Set the column's precision.
     */
    public function precision(int $precision): Column
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        if ($this->precision !== null) {
            return number_format($value, $this->precision);
        }

        return $value;
    }
}
