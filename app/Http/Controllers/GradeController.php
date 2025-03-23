<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use App\Services\GradeService;
use App\Http\Requests\GradeRequest;

class GradeController extends Controller
{
    use HelperTrait;

    protected $gradeService;

    public function __construct(GradeService $service)
    {
        $this->gradeService = $service;
    }

    // List all Grade records
    public function index(Request $request)
    {
        try {
            $data = $this->gradeService->getAll();
            return $this->successResponse($data, 'Grades retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve grades', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create a new Grade record
    public function store(GradeRequest $request)
    {
        $data = $request->validated();
        try {
            $resource = $this->gradeService->create($data);
            return $this->successResponse($resource, 'Grade created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create grade', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve a specific Grade record
    public function show($id)
    {
        try {
            $data = $this->gradeService->getById($id);
            return $this->successResponse($data, 'Grade retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Grade not found', Response::HTTP_NOT_FOUND);
        }
    }

    // Update a specific Grade record
    public function update(GradeRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $resource = $this->gradeService->update($id, $data);
            return $this->successResponse($resource, 'Grade updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update grade', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //grades by medium id
    public function getGradesByMediumId(Request $request, $mediumId)
    {
        try {
            $data = $this->gradeService->getGradesByMediumId($mediumId);
            return $this->successResponse($data, 'Grades retrieved successfully for medium_id: ' . $mediumId, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve grades by medium id', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getGradesByMedium(Request $request)
    {
        try {
            $data = $this->gradeService->getGradesByMedium($request);
            return $this->successResponse($data, 'Grades retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve grades by medium', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }


    
    // Delete a specific Grade record
    public function destroy($id)
    {
        try {
            $this->gradeService->delete($id);
            return $this->successResponse(null, 'Grade deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete grade', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
