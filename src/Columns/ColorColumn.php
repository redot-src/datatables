<?php

namespace Redot\Datatables\Columns;

use Illuminate\Database\Eloquent\Model;

class ColorColumn extends Column
{
    /**
     * The column's width.
     */
    public string $width = '50px';

    /**
     * Prepare the attributes before building.
     */
    protected function prepareAttributes(?Model $row = null): void
    {
        parent::prepareAttributes($row);

        $color = $this->get($row, true);

        $this->css([
            'color: transparent',
            'user-select: none',
            'background-color: ' . $color,
        ]);
    }
}
