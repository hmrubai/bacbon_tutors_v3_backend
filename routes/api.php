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
use App\Http\Controllers\SubjectExpertiseController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserInformationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TutionAreaController;
use App\Http\Controllers\TutorScheduleController;


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
        Route::get('tutor-work-experiences/{user_id}', [TutorWorkExperienceController::class, 'getWorkExperiencesByUserId']);
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

        //Subject Expertise
        Route::get('subject-expertise', [SubjectExpertiseController::class, 'expertiseList']);
        Route::get('subject-expertise/{user_id}', [SubjectExpertiseController::class, 'expertiseListByUser']);
        Route::post('add-subject-expertise', [SubjectExpertiseController::class, 'addExpertise']);
        Route::get('subject-expertise/{id}', [SubjectExpertiseController::class, 'show']);
        Route::post('update-subject-expertise/{id}', [SubjectExpertiseController::class, 'update']);
        Route::delete('delete-subject-expertise/{id}', [SubjectExpertiseController::class, 'destroy']);

        //Reference 
        Route::get('all-references', [ReferenceController::class, 'referenceList']);
        Route::get('references/{user_id}', [ReferenceController::class, 'referenceListByUser']);

        //Address
        Route::get('address/{user_id}', [AddressController::class, 'showByUser']);

        //Document approval 
        Route::post('documents/{id}', [DocumentController::class, 'adminUpdateApproval']);
        Route::delete('documents/{id}', [DocumentController::class, 'destroy']);
        Route::get('documents/{user_id}', [DocumentController::class, 'listByUserId']);

        //tution area
        Route::get('tution-areas/{user_id}', [TutionAreaController::class, 'listByUser']);

        //Tutor schedule
        Route::get('tutor-schedules/{user_id}', [TutorScheduleController::class, 'listByUser']);


    });

    // Tutor Profile for the Tutor
    Route::group(['prefix' => 'tutor'], function () {
        Route::get('education-histories', [TutorController::class, 'getTutorEducationHistory']);
        Route::post('store-education-histories', [TutorController::class, 'storeEducationByUser']);
        Route::post('store-education-histories-by-admin', [TutorController::class, 'storeEducationByAdmin']);
        Route::post('update-education-histories/{id}', [TutorController::class, 'update']);

        // Work Experience
        Route::get('tutor-work-experiences', [TutorWorkExperienceController::class, 'getWorkExperiencesByTutor']);
        Route::post('add-tutor-experience', [TutorWorkExperienceController::class, 'storeExperienceByUser']);
        Route::get('tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'show']);
        Route::post('update-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'update']);
        Route::delete('delete-tutor-work-experience/{id}', [TutorWorkExperienceController::class, 'destroy']);

        //Subject Expertise
        Route::get('tutor-subject-expertise', [SubjectExpertiseController::class, 'getExpertiseByTutor']);
        Route::post('add-subject-expertise', [SubjectExpertiseController::class, 'storeExpertiseByUser']);
        Route::post('update-subject-expertise/{id}', [SubjectExpertiseController::class, 'update']);
        Route::delete('delete-subject-expertise/{id}', [SubjectExpertiseController::class, 'destroy']);

        //reference

        Route::get('tutor-references', [ReferenceController::class, 'index']);
        Route::post('add-reference', [ReferenceController::class, 'store']);
        Route::get('reference/{id}', [ReferenceController::class, 'show']);
        Route::post('update-reference/{id}', [ReferenceController::class, 'update']);
        Route::delete('delete-reference/{id}', [ReferenceController::class, 'destroy']);

        //Address
        Route::post('add-address', [AddressController::class, 'store']);
        Route::get('address', [AddressController::class, 'show']);
        Route::post('update-address', [AddressController::class, 'update']);

        //Basic Information
        Route::get('user-information', [UserInformationController::class, 'show']);
        Route::post('user-information', [UserInformationController::class, 'update']);

        //Document
        Route::get('documents', [DocumentController::class, 'index']);
        Route::post('documents', [DocumentController::class, 'store']);
        Route::get('documents/{id}', [DocumentController::class, 'show']);
        Route::post('documents/{id}', [DocumentController::class, 'update']);
        Route::delete('documents/{id}', [DocumentController::class, 'destroy']);

        //Tution Area
        Route::get('tution-areas', [TutionAreaController::class, 'index']);
        Route::post('tution-areas', [TutionAreaController::class, 'store']);
        Route::post('tution-areas/{id}', [TutionAreaController::class, 'update']);
        Route::delete('tution-areas/{id}', [TutionAreaController::class, 'destroy']);

        //Tutor Schedule
        Route::get('tutor-schedules', [TutorScheduleController::class, 'index']);
        Route::post('tutor-schedules', [TutorScheduleController::class, 'store']);
        Route::get('tutor-schedules/{id}', [TutorScheduleController::class, 'show']);
        Route::post('tutor-schedules/{id}', [TutorScheduleController::class, 'update']);
        Route::delete('tutor-schedules/{id}', [TutorScheduleController::class, 'destroy']);

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
