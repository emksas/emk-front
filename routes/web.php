<?php

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Expenses\ExpensesController;
use App\Http\Controllers\AccountingAccount\AccountingAccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlannedOperationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialPlanningController;

Route::get('/', function () {
    return view('welcome');
});

/*
Route::middleware('auth')->get('/financial-planning', [FinancialPlanningController::class, 'viewPage'])
    ->name('financial-planning.page');
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/api/filters/years', [HomeController::class, 'years'])->name('filters.years');
    Route::get('/api/filters/months', [HomeController::class, 'months'])->name('filters.months');

    Route::get('/api/dashboard', [HomeController::class, 'api'])->name('dashboard.api');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('employee', EmployeeController::class)
    ->names('employee');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/expenses/from-mail', [ExpensesController::class, 'getExpensesFromMail'])->name('expenses.fromMail');

        Route::get('/planning-operation/create/{planId}', [PlannedOperationController::class, 'create'])
            ->name('planning-operation.create');

        Route::post('/planning-operation/{planId}', [PlannedOperationController::class, 'store'])
            ->name('planning-operation.store');

        Route::get('/planning-operation/planification/{planningId}/transaction/{transactionId}', [PlannedOperationController::class, 'edit'])
            ->name('planning-operation.edit');

        Route::put('/planning-operation/planification/{planningId}/transaction/{transactionId}', [PlannedOperationController::class, 'update'])
            ->name('planning-operation.update');

        Route::delete('/planning-operation/planification/{planningId}/transaction/{transactionId}', [PlannedOperationController::class, 'destroy'])
            ->name('planning-operation.destroy');
    })
    ->resource('expenses', ExpensesController::class)
    ->names('expenses');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('accountingAccount', AccountingAccountController::class)
    ->names('accountingAccount');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('financial-planning', FinancialPlanningController::class)
    ->names('financial-planning');
