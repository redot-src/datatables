<?php

namespace Redot\Datatables\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;

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
}
