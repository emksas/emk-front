<?php

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Expenses\ExpensesController;
use App\Http\Controllers\AccountingAccount\AccountingAccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialPlanningController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->get('/financial-planning', [FinancialPlanningController::class, 'viewPage'])
    ->name('financial-planning.page');

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
    })
    ->resource('expenses', ExpensesController::class)
    ->names('expenses');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('accountingAccount', AccountingAccountController::class)
    ->names('accountingAccount');