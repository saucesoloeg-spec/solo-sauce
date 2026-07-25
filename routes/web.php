<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DeputyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\VehicleController;
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
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::put('/sales/{id}', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    Route::middleware(['role:admin|super_admin'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.get');
    });

    Route::middleware(['role:admin|super_admin|manager'])->group(function () {
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('dashboard.orders.show');
    });

    Route::get('/schedules', [SalesController::class, 'schedule'])->name('schedules.get');
    Route::get('/schedules/create', [SalesController::class, 'createSchedule'])->name('schedules.create');
    Route::post('/schedules', [SalesController::class, 'storeSchedule'])->name('schedules.store');

    // API routes for sales schedules
    Route::post('/api/sales/update_visit_date', [SalesController::class, 'updateVisitDate']);
    Route::post('/api/sales/delete_schedule', [SalesController::class, 'deleteSchedule']);

});

Route::middleware([AdminMiddleware::class, 'role:inventory'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/vehicles/{vehicle}', [InventoryController::class, 'show'])->name('inventory.vehicles.show');
});

Route::middleware([AdminMiddleware::class, 'role:manager'])->group(function () {
    Route::get('/manager/orders', [OrderController::class, 'managerIndex'])->name('manager.orders.get');
    Route::post('/manager/orders/{id}/assign-driver', [OrderController::class, 'assignDriver'])->name('manager.orders.assign.driver');
    Route::post('/manager/orders/{id}/assign-deputy', [OrderController::class, 'assignDeputy'])->name('manager.orders.assign.deputy');
    Route::post('/manager/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('manager.orders.cancel');
    Route::post('/manager/orders/{id}/reactivate', [OrderController::class, 'reactivateOrder'])->name('manager.orders.reactivate');

    Route::get('/manager/deputies', [DeputyController::class, 'index'])->name('manager.deputies.get');
    Route::post('/manager/deputies', [DeputyController::class, 'store'])->name('manager.deputies.store');

    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.get');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{id}', [DriverController::class, 'show'])->name('drivers.show');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');

    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.get');
    Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.delete');
    Route::post('/vehicles/{id}/assign-driver', [VehicleController::class, 'assignDriver'])->name('vehicles.assign.driver');
    Route::post('/manager/vehicles/{id}/assign-deputy', [VehicleController::class, 'assignDeputy'])->name('manager.vehicles.assign.deputy');
    Route::post('/manager/vehicles/{id}/unassign-deputy', [VehicleController::class, 'unassignDeputy'])->name('manager.vehicles.unassign.deputy');
    Route::post('/vehicles/{id}/unassign-driver', [VehicleController::class, 'unassignDriver'])->name('vehicles.unassign.driver');
});
    
Route::middleware([AdminMiddleware::class, 'role:super_admin'])->group(function () {
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.get');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins/store', [AdminController::class, 'store'])->name('admins.store');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.get');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
});
