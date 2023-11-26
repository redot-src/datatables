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
        'search' => '<i class="fa fa-search icon"></i>',
        'actions' => '<i class="fa fa-cog icon"></i>',
        'sort-none' => '<i class="fa fa-sort icon"></i>',
        'sort-asc' => '<i class="fa fa-sort-up icon"></i>',
        'sort-desc' => '<i class="fa fa-sort-down icon"></i>',
        'create' => '<i class="fa fa-plus icon dropdown-item-icon"></i>',
        'edit' => '<i class="fa fa-edit icon"></i>',
        'view' => '<i class="fa fa-eye icon"></i>',
        'delete' => '<i class="fa fa-trash icon"></i>',
    ],
];
