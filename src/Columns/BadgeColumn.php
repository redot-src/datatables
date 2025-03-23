<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class BadgeColumn extends Column
{
    /**
     * Determine if the column content is HTML.
     */
    public bool $html = true;

    /**
     * Class list for the element.
     */
    public array $class = ['text-center'];

    /**
     * The column's width.
     */
    public string $width = '50px';

    /**
     * The value to display when the value is true.
     */
    public ?string $true = null;

    /**
     * The value to display when the value is false.
     */
    public ?string $false = null;

    /**
     * Set the value to display when the value is true.
     */
    public function true(string $true): self
    {
        $this->true = $true;

        return $this;
    }

    /**
     * Set the value to display when the value is false.
     */
    public function false(string $false): self
    {
        $this->false = $false;

        return $this;
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        $true = $this->true ?: __('datatables::datatable.yes');
        $false = $this->false ?: __('datatables::datatable.no');

        return sprintf('<span class="badge %s">%s</span>', $value ? 'bg-success-lt' : 'bg-danger-lt', $value ? $true : $false);
    }
}
