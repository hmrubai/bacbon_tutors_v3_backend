<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use App\Services\EmployeeTypeService;
use App\Http\Requests\EmployeeTypeRequest;

class EmployeeTypeController extends Controller
{
    use HelperTrait;

    protected $employeeTypeService;

    public function __construct(EmployeeTypeService $employeeTypeService)
    {
        $this->employeeTypeService = $employeeTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function employeeList(Request $request)
    {
        try {
            $employeeTypes = $this->employeeTypeService->getAll();
            return $this->successResponse($employeeTypes, 'Employment Types retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve Employment Types', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addEmployee(EmployeeTypeRequest $request)
    {
        $data = $request->validated();

        try {
            $employeeType = $this->employeeTypeService->create($data);
            return $this->successResponse($employeeType, 'Employment Type created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create Employment Type', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showSingleType($id)
    {
        try {
            $employeeType = $this->employeeTypeService->getById($id);
            return $this->successResponse($employeeType, 'Employment Type retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Employment Type not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployeeType(EmployeeTypeRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $employeeType = $this->employeeTypeService->update($id, $data);
            return $this->successResponse($employeeType, 'Employment Type updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update Employment Type', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteEmployeeType($id)
    {
        try {
            $this->employeeTypeService->delete($id);
            return $this->successResponse(null, 'Employment Type deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete Employment Type', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
