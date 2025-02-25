<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Open Routes
// Route::post('register', [AuthController::class, 'register']);
Route::post('verify-login', [AuthController::class, 'checkOrCreateUser']);
Route::post('login', [AuthController::class, 'login']);

Route::post('verify-otp', [AuthController::class, 'verifyAndLogin']);

Route::post('send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:5,1');

// Protected Routes
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::get('details', [AuthController::class, 'details']);
});
