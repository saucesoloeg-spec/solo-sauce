<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Surveys\Controllers\SurveyController;

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
    Route::get('/', [SurveyController::class, 'index']);
    Route::post('/', [SurveyController::class, 'store']);
    Route::get('/customer/{customer_id}', [SurveyController::class, 'show']);
});
