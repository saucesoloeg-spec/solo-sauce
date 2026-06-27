<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Drivers\Controllers\DriverAuthController;
use App\Domains\Drivers\Controllers\DriverController;

/*
|--------------------------------------------------------------------------
| Driver API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [DriverAuthController::class, 'register']);
Route::post('/login', [DriverAuthController::class, 'login']);

Route::middleware('auth:drivers')->group(function () {
    Route::get('/profile', [DriverController::class, 'profile']);

    Route::get('/home', [DriverController::class, 'home']);
    Route::get('/dashboard', [DriverController::class, 'dashboard']);
});
