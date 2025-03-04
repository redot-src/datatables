<?php

namespace Redot\Datatables\Columns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DateColumn extends Column
{
    /**
     * The column's type.
     */
    public ?string $type = 'date';

    /**
     * Datetime format.
     */
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Date only format.
     */
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * Time only format.
     */
    public const TIME_FORMAT = 'H:i:s';

    /**
     * Relative time format.
     */
    public const RELATIVE_FORMAT = 'relative';

    /**
     * The column's date format.
     */
    public string $format = self::DATETIME_FORMAT;

    /**
     * Set the column's date format.
     */
    public function format(string $format): Column
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Set the column's date format to datetime.
     */
    public function datetime(): Column
    {
        $this->format = self::DATETIME_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to date only.
     */
    public function date(): Column
    {
        $this->format = self::DATE_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to time only.
     */
    public function time(): Column
    {
        $this->format = self::TIME_FORMAT;

        return $this;
    }

    /**
     * Set the column's date format to relative time.
     */
    public function relative(): Column
    {
        $this->format = 'relative';

        return $this;
    }

    /**
     * Default getter for the column.
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
