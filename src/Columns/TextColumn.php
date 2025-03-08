<?php

namespace Redot\Datatables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;

class TextColumn extends Column
{
    /**
     * The column's type.
     */
    public ?string $type = 'text';

    /**
     * Text prefix for the column.
     */
    public string $prefix = '';

    /**
     * Text suffix for the column.
     */
    public string $suffix = '';

    /**
     * Determine if the text is email.
     */
    public bool $email = false;

    /**
     * Determine if the text is phone number.
     */
    public bool $phone = false;

    /**
     * Determine if the text is URL.
     */
    public bool $url = false;

    /**
     * The url text.
     */
    public string|Closure $urlText = '';

    /**
     * Determine if the URL is external.
     */
    public bool $external = false;

    /**
     * Determine if the text is an icon.
     */
    public bool $icon = false;

    /**
     * Truncate text based on character count.
     */
    public ?int $truncate = null;

    /**
     * Truncate text based on word count.
     */
    public ?int $wordCount = null;

    /**
     * Set the column's prefix.
     */
    public function prefix(string $prefix): Column
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the column's suffix.
     */
    public function suffix(string $suffix): Column
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Set the column's as an email.
     */
    public function email(bool $email = true): Column
    {
        $this->email = $email;

        if ($this->html === false && $email) {
            $this->html = true;
        }

        return $this;
    }

    /**
     * Set the column's as a phone number.
     */
    public function phone(bool $phone = true): Column
    {
        $this->phone = $phone;

        if ($this->html === false && $phone) {
            $this->html = true;
        }

        return $this;
    }

    /**
     * Set the column's as a URL.
     */
    public function url(bool $url = true): Column
    {
        $this->url = $url;

        if ($this->html === false && $url) {
            $this->html = true;
        }

        return $this;
    }

    /**
     * Set the column's URL text.
     */
    public function urlText(string|Closure $text): Column
    {
        $this->urlText = $text;

        return $this;
    }

    /**
     * Set the column's URL as external.
     */
    public function external(bool $external = true): Column
    {
        $this->external = $external;

        return $this;
    }

    /**
     * Set the column's as an icon.
     */
    public function icon(bool $icon = true): Column
    {
        $this->icon = $icon;

        if ($this->html === false && $icon) {
            $this->html = true;
        }

        return $this;
    }

    /**
     * Set the column's truncate length.
     */
    public function truncate(int $length): Column
    {
        $this->truncate = $length;

        return $this;
    }

    /**
     * Set the column's truncate length based on word count.
     */
    public function wordCount(int $wordCount): Column
    {
        $this->wordCount = $wordCount;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        if ($this->prefix) {
            $value = $this->prefix . $value;
        }

        if ($this->suffix) {
            $value = $value . $this->suffix;
        }

        if ($this->email) {
            return sprintf('<a href="mailto:%s">%s</a>', $value, $value);
        }

        if ($this->phone) {
            return sprintf('<a href="tel:%s">%s</a>', $value, $value);
        }

        if ($this->url) {
            $text = $this->urlText instanceof Closure ? call_user_func($this->urlText, $value, $row) : $this->urlText;

            return sprintf('<a href="%s" target="%s">%s</a>', $value, $this->external ? '_blank' : '_self', $text ?: $value);
        }

        if ($this->icon) {
            return sprintf('<i class="%s"></i>', $value);
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
