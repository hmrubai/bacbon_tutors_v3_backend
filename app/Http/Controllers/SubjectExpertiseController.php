<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\SubjectExpertiseService;
use App\Http\Requests\SubjectExpertiseRequest;

class SubjectExpertiseController extends Controller
{
    use HelperTrait;

    protected $expertiseService;

    public function __construct(SubjectExpertiseService $service)
    {
        $this->expertiseService = $service;
    }

    // List all subject expertise records (for admins, for example)
    public function expertiseList(Request $request)
    {
        try {
            $data = $this->expertiseService->getAll();
            return $this->successResponse($data, 'Subject expertise retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve subject expertise', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get subject expertise records for the logged-in tutor
    public function getExpertiseByTutor(Request $request)
    {
        $tutor_id = Auth::id();
        if (!$tutor_id) {
            return $this->errorResponse("Please attach Tutor ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $this->expertiseService->getByTutorId($tutor_id);
            return $this->successResponse($data, 'Subject expertise retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // For tutors: Create new subject expertise (user_id auto-populated)
    public function storeExpertiseByUser(SubjectExpertiseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();  // Auto-populate with logged-in tutor's ID

        try {
            $resource = $this->expertiseService->create($data);
            return $this->successResponse($resource, 'Subject expertise added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create subject expertise', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // For admins: Create subject expertise (user_id must be provided in request)
    public function addExpertise(SubjectExpertiseRequest $request)
    {
        $data = $request->validated();
        try {
            $resource = $this->expertiseService->create($data);
            return $this->successResponse($resource, 'Subject expertise added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create subject expertise', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve a specific subject expertise record
    public function show($id)
    {
        try {
            $data = $this->expertiseService->getById($id);
            return $this->successResponse($data, 'Subject expertise retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Subject expertise not found', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update a subject expertise record
    public function update(SubjectExpertiseRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $resource = $this->expertiseService->update($id, $data);
            return $this->successResponse($resource, 'Subject expertise updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update subject expertise', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a subject expertise record
    public function destroy($id)
    {
        try {
            $this->expertiseService->delete($id);
            return $this->successResponse(null, 'Subject expertise deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete subject expertise', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
