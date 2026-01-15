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
})->middleware(['auth', 'verified', 'check.status'])->name('dashboard');

Route::middleware(['auth', 'check.status'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', Admin\UserController::class);
        Route::resource('apartments', Admin\ApartmentController::class);
        Route::resource('rooms', Admin\RoomController::class);
        Route::resource('tenants', Admin\TenantController::class);
        
        // Tenant Leave Routes
        Route::get('tenants/{tenant}/leave', [Admin\TenantController::class, 'leaveForm'])->name('tenants.leave');
        Route::post('tenants/{tenant}/leave', [Admin\TenantController::class, 'processLeave'])->name('tenants.leave.process');
        
        // Archived Tenants Routes
        Route::get('tenants-archived', [Admin\ArchivedTenantController::class, 'index'])->name('tenants.archived.index');
        Route::get('tenants-archived/{tenant}', [Admin\ArchivedTenantController::class, 'show'])->name('tenants.archived.show');
        Route::post('tenants-archived/{tenant}/restore', [Admin\ArchivedTenantController::class, 'restore'])->name('tenants.archived.restore');
        Route::get('tenants-archived/{tenant}/invoice', [Admin\ArchivedTenantController::class, 'viewInvoice'])->name('tenants.archived.invoice.view');
        Route::get('tenants-archived/{tenant}/invoice/download', [Admin\ArchivedTenantController::class, 'generateInvoice'])->name('tenants.archived.invoice.download');
        Route::delete('tenants-archived/{tenant}', [Admin\ArchivedTenantController::class, 'destroy'])->name('tenants.archived.destroy');
        
        // Revenue & Expense Routes (renamed from Expenses)
        Route::get('expenses/breakeven', [Admin\ExpenseController::class, 'breakeven'])->name('expenses.breakeven');
        Route::post('expenses/sync-payments', [Admin\ExpenseController::class, 'syncPayments'])->name('expenses.sync-payments');
        Route::resource('expenses', Admin\ExpenseController::class);
        
        // Fiscal Period Routes
        Route::resource('fiscal-periods', Admin\FiscalPeriodController::class);
        Route::post('fiscal-periods/{fiscalPeriod}/set-current', [Admin\FiscalPeriodController::class, 'setAsCurrent'])->name('fiscal-periods.set-current');
        Route::post('fiscal-periods/{fiscalPeriod}/close', [Admin\FiscalPeriodController::class, 'close'])->name('fiscal-periods.close');
        Route::get('fiscal-periods/{fiscalPeriod}/export', [Admin\FiscalPeriodController::class, 'exportClosingBalance'])->name('fiscal-periods.export');
        Route::get('fiscal-periods/{fiscalPeriod}/download', [Admin\FiscalPeriodController::class, 'downloadClosingBalance'])->name('fiscal-periods.download');
        
        // Balance Sheet Routes
        Route::resource('balance-sheet', Admin\BalanceSheetController::class);
        
        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::put('users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::get('floors', [Admin\FloorController::class, 'index'])->name('floors.index');
        Route::post('floors', [Admin\FloorController::class, 'store'])->name('floors.store');
        Route::put('floors/{floor}', [Admin\FloorController::class, 'update'])->name('floors.update');
         Route::get('apartments', [Admin\ApartmentController::class,'index'])->name('apartments.index');
        Route::post('apartments', [Admin\ApartmentController::class,'store'])->name('apartments.store');
        Route::put('apartments/{apartment}', [Admin\ApartmentController::class,'update'])->name('apartments.update');

    });

    Route::middleware(['role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [Supervisor\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('customers', Supervisor\CustomerController::class);
        Route::resource('rentals', Supervisor\RentalController::class);
        Route::resource('payments', Supervisor\PaymentController::class);

    //To view Floor and rooms
    Route::get('apartments', function () {
            return view('supervisor.apartments.index', [
                'apartments' => \App\Models\Apartment::with('floor')->get()
            ]);
        })->name('apartments.index');
    });

    Route::middleware(['role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/dashboard', [Tenant\DashboardController::class, 'index'])->name('dashboard');
    });

});

require __DIR__.'/auth.php';
