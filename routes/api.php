<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::apiResource('customers', CustomerController::class);
Route::apiResource('customers.incomes', IncomeController::class)->shallow();;
Route::get('report', [ReportController::class, 'index'])->name('reports.index');
