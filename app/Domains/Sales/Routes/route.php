<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Sales\Controllers\SalesAuthController;

/*
|--------------------------------------------------------------------------
| Sales API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [SalesAuthController::class, 'register']);
Route::post('/login', [SalesAuthController::class, 'login']);

Route::middleware('auth:sales')->group(function () {
    Route::get('/sales_profile', function (Request $request) {
        return $request->user();
    });
});
