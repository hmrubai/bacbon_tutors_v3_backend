<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth Route Configuration
Route::post('check-user-verification', [AuthController::class, 'checkUserVarification']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);

Route::post('verify-login', [AuthController::class, 'checkOrCreateUser']);
Route::post('check-user', [AuthController::class, 'checkOrCreateUser']);

Route::post('login', [AuthController::class, 'login']);

Route::post('verify-otp', [AuthController::class, 'verifyAndLogin']);

Route::post('send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:5,1');

// Protected Routes: Auth Route Configuration
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
    Route::post('set-password', [AuthController::class, 'updatePassword']);
    Route::get('details', [AuthController::class, 'details']);
});
