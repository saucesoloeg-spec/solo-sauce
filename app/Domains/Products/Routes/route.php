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


Route::middleware('auth:sales')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product_id}', [ProductController::class, 'show']);
});
