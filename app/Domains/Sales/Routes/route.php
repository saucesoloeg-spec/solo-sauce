<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Sales\Controllers\SalesController;
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

Route::get('/all/{city_id?}', [SalesController::class, 'show']);
Route::middleware('auth:sales')->group(function () {
    Route::get('/sales_profile', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard', [SalesController::class, 'index']);

    Route::post('/logout', [SalesAuthController::class, 'logout']);
    Route::get('/schedule', [SalesController::class, 'schedule']);
    Route::get('/schedule-history', [SalesController::class, 'scheduleHistory']);
    Route::get('/cancel-schedule/{id}', [SalesController::class, 'cancelSchedule']);

});
    