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

$css = config('datatables.assets.css');
$js = config('datatables.assets.js');

Route::get($css['route'], fn () => response()->file($css['path'], ['Content-Type' => 'text/css']))->name($css['name']);
Route::get($js['route'], fn () => response()->file($js['path'], ['Content-Type' => 'text/javascript']))->name($js['name']);
