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

foreach (config('datatables.assets') as $asset) {
    Route::get($asset['uri'], function () use ($asset) {
        $path = $asset['file'];
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return response()->file($path, ['Content-Type' => 'text/' . $extension]);
    })->name($asset['route']);
}
