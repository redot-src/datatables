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
}
