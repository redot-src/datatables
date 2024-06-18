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
     * Determine if the text is email.
     *
     * @var bool
     */
    protected bool $email = false;

    /**
     * Determine if the text is phone number.
     *
     * @var bool
     */
    protected bool $phone = false;

    /**
     * Determine if the text is URL.
     *
     * @var bool
     */
    protected bool $url = false;

    /**
     * Determine if the URL is external.
     *
     * @var bool
     */
    protected bool $external = false;

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
     * Set the column's prefix.
     *
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix): Column
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the column's suffix.
     *
     * @param string $suffix
     * @return $this
     */
    public function suffix(string $suffix): Column
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Set the column's email type.
     *
     * @param bool $email
     * @return $this
     */
    public function email(bool $email = true): Column
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the column's phone type.
     *
     * @param bool $phone
     * @return $this
     */
    public function phone(bool $phone = true): Column
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Set the column's URL type.
     *
     * @param bool $url
     * @return $this
     */
    public function url(bool $url = true): Column
    {
        $this->url = $url;
        $this->external = strpos($this->url, 'http') === 0;

        return $this;
    }

    /**
     * Set the column's external URL type.
     *
     * @param bool $external
     * @return $this
     */
    public function external(bool $external = true): Column
    {
        $this->external = $external;

        return $this;
    }

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

        if ($this->email) {
            return sprintf('<a href="mailto:%s">%s</a>', $value, $value);
        }

        if ($this->phone) {
            return sprintf('<a href="tel:%s">%s</a>', $value, $value);
        }

        if ($this->url) {
            return sprintf('<a href="%s" target="%s">%s</a>', $value, $this->external ? '_blank' : '_self', $value);
        }

        if ($this->truncate !== null) {
            return \Illuminate\Support\Str::limit($value, $this->truncate);
        }

        if ($this->wordCount !== null) {
            return \Illuminate\Support\Str::words($value, $this->wordCount);
        }

        return $value;
    }
}
