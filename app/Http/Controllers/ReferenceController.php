<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\ReferenceService;
use App\Http\Requests\ReferenceRequest;

class ReferenceController extends Controller
{
    use HelperTrait;

    protected $referenceService;

    public function __construct(ReferenceService $service)
    {
        $this->referenceService = $service;
    }

    public function referenceList(Request $request)
    {
        try {
            $data = $this->referenceService->getAll();
            return $this->successResponse($data, 'References retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve references', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function referenceListByUser(Request $request, $user_id)
    {
        try {
            // Use the service method to retrieve references for the given user_id
            $data = $this->referenceService->getByTutorId($user_id);
            return $this->successResponse($data, "References for user_id: {$user_id} retrieved successfully!", Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve references for the specified user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // List all references for the logged-in tutor
    public function index(Request $request)
    {
        $tutor_id = Auth::id();
        if (!$tutor_id) {
            return $this->errorResponse("Tutor ID not found", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $data = $this->referenceService->getByTutorId($tutor_id);
            return $this->successResponse($data, 'References retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve references', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create a new reference (tutors only)
    public function store(ReferenceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();  // Auto-populate tutor's user_id

        try {
            $resource = $this->referenceService->create($data);
            return $this->successResponse($resource, 'Reference created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create reference', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve a specific reference record (ensuring ownership)
    public function show($id)
    {
        try {
            $reference = $this->referenceService->getById($id);
            // Ensure the logged-in tutor owns this reference
            if ($reference->user_id != Auth::id()) {
                return $this->errorResponse("Unauthorized", "You do not have permission to view this record", Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse($reference, 'Reference retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Reference not found', Response::HTTP_NOT_FOUND);
        }
    }

    // Update a reference record (tutors only)
    public function update(ReferenceRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $reference = $this->referenceService->getById($id);
            // Ensure the logged-in tutor owns this reference
            if ($reference->user_id != Auth::id()) {
                return $this->errorResponse("Unauthorized", "You do not have permission to update this record", Response::HTTP_FORBIDDEN);
            }
            $resource = $this->referenceService->update($id, $data);
            return $this->successResponse($resource, 'Reference updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update reference', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a reference record (tutors only)
    public function destroy($id)
    {
        try {
            $reference = $this->referenceService->getById($id);
            // Ensure the logged-in tutor owns this reference
            if ($reference->user_id != Auth::id()) {
                return $this->errorResponse("Unauthorized", "You do not have permission to delete this record", Response::HTTP_FORBIDDEN);
            }
            $this->referenceService->delete($id);
            return $this->successResponse(null, 'Reference deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete reference', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
