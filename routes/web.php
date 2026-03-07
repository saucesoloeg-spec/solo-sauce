<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalesController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Sales;
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

Route::get('/', function () {
    return redirect()->route('admin.get.login');
});
 
Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }
 
    session()->put('locale', $locale);
    return redirect()->route('dashboard'); 
});

Route::get('/login', [AdminController::class, 'getlogin'])->name('admin.get.login');
Route::post('/login', [AdminController::class, 'postlogin'])->name('admin.post.login');
Route::get('/register', [AdminController::class, 'getRegister'])->name('admin.get.register');
Route::post('/register', [AdminController::class, 'postRegister'])->name('admin.post.register');

Route::get('/password_forget', [AdminController::class, 'forgotPassword'])->name('admin.password.forget');
Route::post('/forgot_password', [AdminController::class, 'sendResetLink'])->name('admin.forgot.password');
Route::get('/reset_password/{token}', [AdminController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset_password', [AdminController::class, 'resetPassword'])->name('reset.password');

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.get');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.get');
    Route::get('/sales/{id}', [SalesController::class, 'show'])->name('sales.show');

});
