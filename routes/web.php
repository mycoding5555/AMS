<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Supervisor;
use App\Http\Controllers\Tenant;    

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', Admin\UserController::class);
        Route::resource('apartments', Admin\ApartmentController::class);
        Route::resource('expenses', Admin\ExpenseController::class);
    });

    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::resource('customers', Supervisor\CustomerController::class);
        Route::resource('rentals', Supervisor\RentalController::class);
        Route::resource('payments', Supervisor\PaymentController::class);
    });

    Route::middleware(['role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/dashboard', [Tenant\DashboardController::class, 'index'])->name('dashboard');
    });

});

require __DIR__.'/auth.php';
