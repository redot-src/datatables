<?php

namespace Redot\Datatables\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Datatable
{
    /**
     * Get the query source of the datatable.
     *
     * @return Builder
     */
    public function query(): Builder;

    /**
     * Get the columns for the datatable.
     *
     * @return Column[]
     */
    public function columns(): array;

    /**
     * Get the filters for the datatable.
     *
     * @return Filter[]
     */
    public function filters(): array;

    /**
     * Get the actions for the datatable.
     *
     * @return Action[]
     */
    public function actions(): array;

    /**
     * Export the datatable to a XLSX file.
     *
     * @return void
     */
    public function toXlsx(): void;

    /**
     * Export the datatable to a CSV file.
     *
     * @return void
     */
    public function toCsv(): void;

    /**
     * Export the datatable to a PDF file.
     *
     * @return void
     */
    public function toPdf(): void;

    /**
     * Export the datatable to a JSON file.
     *
     * @return void
     */
    public function toJson(): void;
}
