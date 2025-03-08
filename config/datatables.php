<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Redot Datatables config
    |--------------------------------------------------------------------------
    |
    | Here you can specify the configuration of the redot datatable.
    |
    */

    'assets' => [
        'css' => [
            'path' => base_path('vendor/redot/datatables/resources/css/datatables.css'),
            'route' => '/__datatables/datatables.css',
            'name' => 'datatables.css',
        ],
        'js' => [
            'path' => base_path('vendor/redot/datatables/resources/js/datatables.js'),
            'route' => '/__datatables/datatables.js',
            'name' => 'datatables.js',
        ],
    ],
];