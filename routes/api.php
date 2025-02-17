<?php

use App\Http\Controllers\TutorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\TutorWorkExperienceController;

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

        // Work Experience
        Route::get('tutor-work-experiences', [TutorWorkExperienceController::class, 'experienceList']);
        Route::post('add-tutor-experience', [TutorWorkExperienceController::class, 'addExperience']);
        Route::get('tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'show']);
        Route::post('update-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'update']);
        Route::delete('delete-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'destroy']);

    });

    // Tutor Profile for the Tutor
    Route::group(['prefix' => 'tutor'], function(){
        Route::get('education-histories', [TutorController::class, 'getTutorEducationHistory']);
        Route::post('store-education-histories', [TutorController::class, 'storeEducationByUser']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);

        // Work Experience
        Route::get('tutor-work-experiences', [TutorWorkExperienceController::class, 'experienceList']);
        Route::post('add-tutor-experience', [TutorWorkExperienceController::class, 'storeExperienceByUser']);
        Route::get('tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'show']);
        Route::post('update-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'update']);
        Route::delete('delete-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'destroy']);
    });

    Route::get('division-list', [LocationController::class, 'divisionList']);
    Route::get('district-list-by-id/{division_id}', [LocationController::class, 'districtListByID']);
    Route::get('upazila-list-by-id/{district_id}', [LocationController::class, 'upazilaListByID']);
    Route::get('area-list-by-id/{upazila_id}', [LocationController::class, 'unionListByID']);
    
    //Employment Type
    Route::get('all-employment-types', [EmployeeTypeController::class, 'employeeList']);
    Route::post('add-employment-type', [EmployeeTypeController::class, 'addEmployee']);
    Route::post('update-employment-type/{id}', [EmployeeTypeController::class, 'updateEmployeeType']);
    Route::delete('delete-employment-type/{id}', [EmployeeTypeController::class, 'destroy']);

    Route::apiResource('categories', CategoryController::class);
});
