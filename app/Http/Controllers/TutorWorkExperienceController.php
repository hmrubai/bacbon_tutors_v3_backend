<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\TutorWorkExperienceService;
use App\Http\Requests\TutorWorkExperienceRequest;

class TutorWorkExperienceController extends Controller
{
    use HelperTrait;

    protected $workExpService;

    public function __construct(TutorWorkExperienceService $service)
    {
        $this->workExpService = $service;
    }

    // List all work experiences (for admins, for example)
    public function experienceList(Request $request)
    {
        try {
            $data = $this->workExpService->getAll();
            return $this->successResponse($data, 'Tutor work experiences retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get work experiences for the logged-in tutor
    public function getWorkExperiencesByTutor(Request $request)
    {
        $tutor_id = Auth::id();
        if (!$tutor_id) {
            return $this->errorResponse("Please attach Tutor ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $this->workExpService->getByTutorId($tutor_id);
            return $this->successResponse($data, 'Tutor work experiences retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // For tutors: Create a new work experience
    public function storeExperienceByUser(TutorWorkExperienceRequest $request)
    {
        $data = $request->validated();
        // Automatically attach the authenticated tutor's user ID
        $data['user_id'] = Auth::id();
        try {
            $resource = $this->workExpService->create($data);
            return $this->successResponse($resource, 'Work experience added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create work experience', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // For admins: Create a work experience (user_id must be provided in the request)
    public function addExperience(TutorWorkExperienceRequest $request)
    {
        $data = $request->validated();
        try {
            $resource = $this->workExpService->create($data);
            return $this->successResponse($resource, 'Work experience added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create work experience', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve a specific work experience record
    public function show($id)
    {
        try {
            $data = $this->workExpService->getById($id);
            return $this->successResponse($data, 'Work experience retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Work experience not found', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update a work experience record
    public function update(TutorWorkExperienceRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $resource = $this->workExpService->update($id, $data);
            return $this->successResponse($resource, 'Work experience updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update work experience', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a work experience record
    public function destroy($id)
    {
        try {
            $this->workExpService->delete($id);
            return $this->successResponse(null, 'Work experience deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete work experience', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
