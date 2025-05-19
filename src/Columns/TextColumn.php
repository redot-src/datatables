<?php

namespace Redot\Datatables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;

class TextColumn extends Column
{
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
     * Url text column options.
     */
    public array $urlOptions = [
        'text' => null,
        'fancybox' => false,
        'target' => '_self',
        'route' => null,
        'parameters' => [],
    ];

    /**
     * Truncate text based on character count.
     */
    public ?int $truncate = null;

    /**
     * Truncate text based on word count.
     */
    public ?int $wordCount = null;

    /**
     * Pad the column's value.
     */
    public ?int $pad = null;

    /**
     * Padding character.
     */
    public string $padChar = ' ';

    /**
     * Padding direction.
     */
    public int $padDir = STR_PAD_RIGHT;

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
    public function url(bool $url = true, string|Closure|null $text = null, bool $fancybox = false, string $target = '_self'): Column
    {
        $this->url = $url;
        $this->urlOptions['text'] = $text;
        $this->urlOptions['fancybox'] = $fancybox;
        $this->urlOptions['target'] = $target;

        if ($this->html === false && $url) {
            $this->html = true;
        }

        return $this;
    }

    /**
     * Set the column's URL route.
     */
    public function route(string $route, array $parameters = [], string|Closure|null $text = null, bool $fancybox = false, string $target = '_self'): Column
    {
        $this->urlOptions['route'] = $route;
        $this->urlOptions['parameters'] = $parameters;

        return $this->url(true, $text, $fancybox, $target);
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
     * Set the column's padding.
     */
    public function pad(int $length, string $char = ' ', int $dir = STR_PAD_RIGHT): Column
    {
        $this->pad = $length;
        $this->padChar = $char;
        $this->padDir = $dir;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        if (is_null($value)) {
            return null;
        }

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
            $urlOptions = $this->urlOptions;

            if ($urlOptions['route']) {
                $parameters = $urlOptions['parameters'];
                foreach ($parameters as $key => $parameter) {
                    $parameters[$key] = $this->evaluate($parameter, $value, $row);
                }

                $url = route($urlOptions['route'], array_merge([$row], $parameters));
            } else {
                $url = $value;
            }

            return sprintf('<a href="%s" target="%s"%s>%s</a>',
                $url,
                $urlOptions['target'],
                $urlOptions['fancybox'] ? ' data-fancybox' : '',
                $this->evaluate($urlOptions['text'], $value, $row) ?? $value,
            );
        }

        if ($this->truncate !== null) {
            return \Illuminate\Support\Str::limit($value, $this->truncate);
        }

        if ($this->wordCount !== null) {
            return \Illuminate\Support\Str::words($value, $this->wordCount);
        }

        if ($this->pad !== null) {
            return str_pad($value, $this->pad, $this->padChar, $this->padDir);
        }

        return $value;
    }
}
