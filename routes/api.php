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
use App\Http\Controllers\MediumController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;

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
    Route::group(['prefix' => 'admin'], function () {
        Route::get('all-tutor-education-histories', [TutorController::class, 'index']);
        Route::get('tutor-education-histories', [TutorController::class, 'tutorEducationForAdmin']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);

        // Work Experience
        Route::get('tutor-work-experiences', [TutorWorkExperienceController::class, 'experienceList']);
        Route::post('add-tutor-experience', [TutorWorkExperienceController::class, 'addExperience']);
        Route::get('tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'show']);
        Route::post('update-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'update']);
        Route::delete('delete-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'destroy']);

        //Medium
        Route::get('all-mediums', [MediumController::class, 'index']);
        Route::post('add-medium', [MediumController::class, 'store']);
        Route::get('medium/{id}', [MediumController::class, 'show']);
        Route::post('update-medium/{id}', [MediumController::class, 'update']);
        Route::delete('delete-medium/{id}', [MediumController::class, 'destroy']);

        //Grade
        Route::get('all-grades', [GradeController::class, 'index']);
        Route::post('add-grade', [GradeController::class, 'store']);
        Route::get('grade/{id}', [GradeController::class, 'show']);
        Route::post('update-grade/{id}', [GradeController::class, 'update']);
        Route::get('grades/medium/{mediumId}', [GradeController::class, 'getGradesByMediumId']);
        Route::delete('delete-grade/{id}', [GradeController::class, 'destroy']);

        //Subject
        Route::get('all-subjects', [SubjectController::class, 'index']);
        Route::post('add-subject', [SubjectController::class, 'store']);
        Route::get('subject/{id}', [SubjectController::class, 'show']);
        Route::post('update-subject/{id}', [SubjectController::class, 'update']);
        Route::get('subjects/medium/{mediumId}', [SubjectController::class, 'getSubjectsByMediumId']);
        Route::delete('delete-subject/{id}', [SubjectController::class, 'destroy']);

    });

    // Tutor Profile for the Tutor
    Route::group(['prefix' => 'tutor'], function () {
        Route::get('education-histories', [TutorController::class, 'getTutorEducationHistory']);
        Route::post('store-education-histories', [TutorController::class, 'storeEducationByUser']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);

        // Work Experience
        Route::get('tutor-work-experiences', [TutorWorkExperienceController::class, 'getWorkExperiencesByTutor']);
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
