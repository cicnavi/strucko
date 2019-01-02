<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

// Catch all to redirect non existent routes to SPA.
// If needed use non api regex: ^(?!api).*$'
Route::get('/{catchall?}', function () {
    return view('home');
})->where('catchall', '.*');
