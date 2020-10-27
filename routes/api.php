<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Investments;
use App\Http\Controllers\Users;
use App\Http\Controllers\Packages;
use App\Http\Controllers\Images;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post("/user/signup", [Users::class, "signup"]);
Route::post("/user/signin", [Users::class, "signin"]);

Route::get("/packages/getall", [Packages::class, "getAll"]);

Route::post("/packages/add", [Packages::class, "add"]);
Route::post("/packages/save", [Packages::class, "save"]);

Route::get("/investments/save", [Investments::class, "getAll"]);

Route::post("/images/upload", [Images::class, "upload"]);
Route::get("/images/get", [Images::class, "get"]);