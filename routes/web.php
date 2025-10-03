<?php

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Expenses\ExpensesController;
use App\Http\Controllers\AccountingAccount\AccountingAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('employee', EmployeeController::class) 
    ->names('employee');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function(){
        Route::get('/expenses/from-mail', [ExpensesController::class, 'getExpensesFromMail'])->name('expenses.fromMail');
    })
    ->resource('expenses', ExpensesController::class) 
    ->names('expenses');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->resource('accountingAccount', AccountingAccountController::class) 
    ->names('accountingAccount');