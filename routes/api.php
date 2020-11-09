<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Investments;
use App\Http\Controllers\Users;
use App\Http\Controllers\Packages;
use App\Http\Controllers\Images;
use App\Http\Controllers\Home;
use App\Http\Controllers\Contact;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/user/get', [Users::class, 'getUser']);
Route::post('/user/signup', [Users::class, 'signup']);
Route::post('/user/signin', [Users::class, 'signin']);
Route::get('/user/signout', [Users::class, 'signout']);
Route::get('/user/recovery/info', [Users::class, 'recoveryInfo']);
Route::post('/user/recovery/verify', [Users::class, 'recoveryVerify']);

Route::post('/user/settings/changePassword', [Users::class, 'changePassword']);

Route::get('/packages/getall', [Packages::class, 'getAll']);
Route::get('/packages/getById/{id}', [Packages::class, 'getById']);

Route::post('/packages/add', [Packages::class, 'add']);
Route::post('/packages/save', [Packages::class, 'save']);

Route::get('/investments/save', [Investments::class, 'getAll']);

Route::post('/images/upload', [Images::class, 'upload']);
Route::get('/images/get', [Images::class, 'get']);

Route::get('/home/get-data', [Home::class, 'getData']);


Route::fallback(function () {
    return response()->json(
        ['message' => 'Page Not Found. If the error persist, contact info@jetfarmsng.com'], 404
    );
});
