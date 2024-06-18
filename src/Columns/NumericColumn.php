<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class NumericColumn extends Column
{
    /**
     * The column's type.
     *
     * @var string
     */
    protected string $type = 'numeric';

    /**
     * The column's precision.
     *
     * @var int|null
     */
    protected int|null $precision = null;

    /**
     * Set the column's precision.
     *
     * @param int $precision
     * @return $this
     */
    public function precision(int $precision): Column
    {
        $this->precision = $precision;

        return $this;
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
        if ($this->precision !== null) {
            return number_format($value, $this->precision);
        }

        return $value;
    }
}
