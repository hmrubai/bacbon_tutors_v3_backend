<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Protected Routes
Route::group(['middleware' => ['auth:api',]], function () {
    Route::group(['middleware' => ['role:system-admin,super-admin,admin']], function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('sub-menus', SubMenuController::class);
    });
    Route::apiResource('categories', CategoryController::class);
});
