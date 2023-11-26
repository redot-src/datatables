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

    /**
     * Datatable templates.
     */
    'templates' => [
        'datatable' => 'livewire-datatable::datatable',
        'row-action' => 'livewire-datatable::row-action',
        'header-button' => 'livewire-datatable::header-button',

        'pagination' => [
            'default' => 'livewire-datatable::pagination.default',
            'simple' => 'livewire-datatable::pagination.simple',
        ],
    ],

    'icons' => [
        'search' => '<i class="fa fa-search"></i>',
        'actions' => '<i class="fa fa-cog"></i>',
        'sort-none' => '<i class="fa fa-sort"></i>',
        'sort-asc' => '<i class="fa fa-sort-up"></i>',
        'sort-desc' => '<i class="fa fa-sort-down"></i>',
        'create' => '<i class="fa fa-plus"></i>',
        'edit' => '<i class="fa fa-edit"></i>',
        'view' => '<i class="fa fa-eye"></i>',
        'delete' => '<i class="fa fa-trash"></i>',
    ],
];
