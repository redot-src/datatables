<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Livewire Datatable config
    |--------------------------------------------------------------------------
    |
    | Here you can specify the configuration of the livewire-datatable.
    |
    */

    'pagination_view' => 'livewire-datatable::pagination.default',
    'pagination_simple_view' => 'livewire-datatable::pagination.simple',
    'template' => 'livewire-datatable::datatable',

    'icons' => [
        'search' => '<i class="fa fa-search"></i>',
        'create' => '<i class="fa fa-plus"></i>',
        'sort-none' => '<i class="fa fa-sort"></i>',
        'sort-asc' => '<i class="fa fa-sort-amount-up"></i>',
        'sort-desc' => '<i class="fa fa-sort-amount-down"></i>',
        'edit' => '<i class="fa fa-edit"></i>',
        'view' => '<i class="fa fa-eye"></i>',
        'delete' => '<i class="fa fa-trash"></i>',
    ],
];
