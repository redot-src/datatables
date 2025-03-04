<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Datatable Routes
|--------------------------------------------------------------------------
|
| Here is the redot datatables routes to serve the datatable assets.
|
*/

Route::get('/__datatables/datatables.css', function () {
    $path = __DIR__ . '/../resources/css/datatables.css';

    return response()->file($path, ['Content-Type' => 'text/css']);
})->name('__datatables.css');

Route::get('/__datatables/datatables.js', function () {
    $path = __DIR__ . '/../resources/js/datatables.js';

    return response()->file($path, ['Content-Type' => 'text/javascript']);
})->name('__datatables.js');
