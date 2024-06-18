<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class TextColumn extends Column
{
    /**
     * The column's type.
     *
     * @var string
     */
    protected string $type = 'text';

    /**
     * Determine whether the text should be truncated.
     *
     * @var int|null
     */
    protected int|null $truncate = null;

    /**
     * Set the column's truncate length.
     *
     * @param int $length
     * @return $this
     */
    public function truncate(int $length): Column
    {
        $this->truncate = $length;

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
        if ($this->truncate !== null) {
            return \Illuminate\Support\Str::limit($value, $this->truncate);
        }

        return $value;
    }
}
