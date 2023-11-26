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

    /**
     * Datatable icons.
     */
    'icons' => [
        'search' => 'fa fa-search',
        'actions' => 'fa fa-cog text-muted',
        'sort-none' => 'fa fa-sort',
        'sort-asc' => 'fa fa-sort-up',
        'sort-desc' => 'fa fa-sort-down',
        'create' => 'fa fa-plus',
        'edit' => 'fa fa-edit',
        'view' => 'fa fa-eye',
        'delete' => 'fa fa-trash',
    ],
];
