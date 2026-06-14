<?php

use App\Domains\Products\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:admin,sales')->group(function () {
    Route::post('/', [ProductController::class, 'index']);
});

Route::middleware('auth:admin,sales,drivers')->group(function () {
    Route::get('/{product_id}', [ProductController::class, 'show']);
});
