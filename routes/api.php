<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
 
  
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
     
Route::middleware('auth:api')->group( function () {
    Route::get('accounts/{accountNumber?}', [AccountController::class, 'index']);
    Route::post('accounts', [AccountController::class, 'store']);

    
    Route::get('accounts/{accountNumber}/transactions', [TransactionController::class, 'index']);
    Route::post('accounts/{accountNumber}/transactions', [TransactionController::class, 'store']);
});