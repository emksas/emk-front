<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/monthly-expenses', [\App\Http\Controllers\Api\ExpensesController::class, 'monthlyExpenses']);

Route::post('/auth/instrospect', [AuthInstrospectionController::class, 'instrospect']);