<?php

use App\Http\Controllers\Auth\mobile\AuthMobileController;
use Illuminate\Support\Facades\Route;

Route::namespace('Mobile\Auth')->prefix('v2/mobile')->middleware(['middleware' => 'mobile'])->group(function() {

    Route::post('/register'                         ,[AuthMobileController::class, 'register']);
    Route::post('/confirm-account'                  ,[AuthMobileController::class, 'confirmByCode']);
    Route::post('/login'                            ,[AuthMobileController::class, 'login']);
    Route::post('/logout'                           ,[AuthMobileController::class, 'logout']);
    Route::post('/refresh'                          ,[AuthMobileController::class, 'refresh']);
    Route::post('/profile'                          ,[AuthMobileController::class, 'profile']);
    Route::post('/request-reset-password'           ,[AuthMobileController::class, 'requestResetPassword']);
    Route::post('/reset-password'                   ,[AuthMobileController::class, 'resetPassword']);
});