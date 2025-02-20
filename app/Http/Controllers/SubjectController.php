<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use App\Services\SubjectService;
use App\Http\Requests\SubjectRequest;

class SubjectController extends Controller
{
    use HelperTrait;

    protected $subjectService;

    public function __construct(SubjectService $service)
    {
        $this->subjectService = $service;
    }

    // List all subjects
    public function index(Request $request)
    {
        try {
            $data = $this->subjectService->getAll();
            return $this->successResponse($data, 'Subjects retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve subjects', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create a new subject
    public function store(SubjectRequest $request)
    {
        $data = $request->validated();

        try {
            $resource = $this->subjectService->create($data);
            return $this->successResponse($resource, 'Subject created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve a specific subject
    public function show($id)
    {
        try {
            $data = $this->subjectService->getById($id);
            return $this->successResponse($data, 'Subject retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Subject not found', Response::HTTP_NOT_FOUND);
        }
    }

    // Update a subject
    public function update(SubjectRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $resource = $this->subjectService->update($id, $data);
            return $this->successResponse($resource, 'Subject updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
     // New API: Get subject list by medium_id
     public function getSubjectsByMediumId(Request $request, $mediumId)
     {
         try {
             $data = $this->subjectService->getSubjectsByMediumId($mediumId);
             return $this->successResponse($data, "Subjects retrieved successfully for medium_id: $mediumId", Response::HTTP_OK);
         } catch (\Throwable $th) {
             return $this->errorResponse($th->getMessage(), 'Failed to retrieve subjects by medium id', Response::HTTP_INTERNAL_SERVER_ERROR);
         }
     }

    // Delete a subject
    public function destroy($id)
    {
        try {
            $this->subjectService->delete($id);
            return $this->successResponse(null, 'Subject deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
