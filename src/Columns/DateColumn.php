<?php

namespace Redot\Datatables\Columns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DateColumn extends Column
{
    /**
     * The column's type.
     *
     * @var string
     */
    protected string $type = 'date';

    /**
     * Datetime format.
     *
     * @var string
     */
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Date only format.
     *
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * Time only format.
     *
     * @var string
     */
    public const TIME_FORMAT = 'H:i:s';

    /**
     * Relative time format.
     *
     * @var string
     */
    public const RELATIVE_FORMAT = 'relative';

    /**
     * The column's date format.
     *
     * @var string
     */
    protected string $format = self::DATETIME_FORMAT;

    /**
     * Set the column's date format.
     *
     * @param string $format
     * @return $this
     */
    public function format(string $format): Column
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Set the column's date format to datetime.
     *
     * @return $this
     */
    public function datetime(): Column
    {
        $this->format = self::DATETIME_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to date only.
     *
     * @return $this
     */
    public function date(): Column
    {
        $this->format = self::DATE_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to time only.
     *
     * @return $this
     */
    public function time(): Column
    {
        $this->format = self::TIME_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to relative time.
     *
     * @return $this
     */
    public function relative(): Column
    {
        $this->format = 'relative';

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
        if (! $value instanceof Carbon) {
            $value = Carbon::parse($value);
        }

        if ($this->format === 'relative') {
            return $value->diffForHumans();
        }

        return $value->translatedFormat($this->format);
    }
}
