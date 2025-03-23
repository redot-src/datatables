<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class BadgeColumn extends Column
{
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
     * Initialize the column.
     */
    public function init(): void
    {
        $this->true ??= __('datatables::datatable.yes');
        $this->false ??= __('datatables::datatable.no');
    }

    /**
     * Default getter for the column.
     */
    protected function defaultGetter(mixed $value, Model $row): mixed
    {
        return $value ? $this->true : $this->false;
    }

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        parent::prepareAttributes($row);

        // Get the value of the column.
        $value = $this->get($row, true);

        if ($value === $this->true) {
            $this->class('bg-success-lt');
        } else {
            $this->class('bg-danger-lt');
        }
    }
}
