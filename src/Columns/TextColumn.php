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
     * Text prefix for the column.
     *
     * @var string
     */
    protected string $prefix = '';

    /**
     * Text suffix for the column.
     *
     * @var string
     */
    protected string $suffix = '';

    /**
     * Truncate text based on character count.
     *
     * @var int|null
     */
    protected int|null $truncate = null;

    /**
     * Truncate text based on word count.
     *
     * @var int|null
     */
    protected int|null $wordCount = null;

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
     * Set the column's truncate length based on word count.
     *
     * @param int $wordCount
     * @return $this
     */
    public function wordCount(int $wordCount): Column
    {
        $this->wordCount = $wordCount;

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
        $value = $this->prefix . $value . $this->suffix;

        if ($this->truncate !== null) {
            return \Illuminate\Support\Str::limit($value, $this->truncate);
        }

        if ($this->wordCount !== null) {
            return \Illuminate\Support\Str::words($value, $this->wordCount);
        }

        return $value;
    }
}
