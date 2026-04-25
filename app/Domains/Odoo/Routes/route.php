<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Odoo\Controllers\OdooController;

/*
|--------------------------------------------------------------------------
| Odoo API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/states/{country_id}', [OdooController::class, 'states']);
Route::get('/cities/{state_id}', [OdooController::class, 'cities']);
