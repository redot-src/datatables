<?php

namespace Redot\Datatables;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;
use Livewire\Component;
use Redot\Datatables\Contracts\Column;
use Redot\Datatables\Contracts\Filter;
use Redot\Datatables\Contracts\Action;
use Redot\Datatables\Contracts\Datatable as DatatableContract;

abstract class Datatable extends Component implements DatatableContract
{
    use Macroable;

    /**
     * Model bound to the datatable.
     *
     * @var string
     */
    protected string $model;

    /**
     * Get the query source of the datatable.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        if (isset($this->model)) {
            return app($this->model)->query();
        }

        throw new Exceptions\ResourceNotFoundException('Resource not found. Please set the model property in your datatable class.');
    }

    /**
     * Get the columns for the datatable.
     *
     * @return Column[]
     */
    public function columns(): array
    {
        return [];
    }

    /**
     * Get the filters for the datatable.
     *
     * @return Filter[]
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Get the actions for the datatable.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Export the datatable to a XLSX file.
     *
     * @return void
     */
    public function toXlsx(): void
    {
        // ...
    }

    /**
     * Export the datatable to a CSV file.
     *
     * @return void
     */
    public function toCsv(): void
    {
        // ...
    }

    /**
     * Export the datatable to a PDF file.
     *
     * @return void
     */
    public function toPdf(): void
    {
        // ...
    }

    /**
     * Export the datatable to a JSON file.
     *
     * @return void
     */
    public function toJson(): void
    {
        // ...
    }
}
