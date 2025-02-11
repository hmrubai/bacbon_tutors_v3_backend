<?php

use App\Http\Controllers\TutorController;
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
    
    // Tutor Profile
    // Route::group(['middleware' => ['role:system-admin,super-admin,admin']], function () {
    //     Route::apiResource('tutor-education-histories', TutorController::class);
    // });

    //Route::apiResource('tutor-education-histories', TutorController::class);

    // Tutor Profile for the Administrator
    Route::group(['prefix' => 'admin'], function(){
        Route::get('all-tutor-education-histories', [TutorController::class, 'index']);
        Route::get('tutor-education-histories', [TutorController::class, 'tutorEducationForAdmin']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);
    });

    // Tutor Profile for the Tutor
    Route::group(['prefix' => 'tutor'], function(){
        Route::get('education-histories', [TutorController::class, 'getTutorEducationHistory']);
        Route::post('store-education-histories', [TutorController::class, 'storeEducationByUser']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);
    });

    Route::apiResource('categories', CategoryController::class);
});
