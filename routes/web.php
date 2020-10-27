<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/server-status', function () {
    return "Server up and running";
});


Route::fallback(function () {
    return response()->json(
        ['message' => 'Page Not Found. If the error persist, contact info@jetfarmsng.com'], 404
    );
});
