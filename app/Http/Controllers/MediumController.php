<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use App\Services\MediumService;
use App\Http\Requests\MediumRequest;

class MediumController extends Controller
{
    use HelperTrait;

    protected $mediumService;

    public function __construct(MediumService $service)
    {
        $this->mediumService = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $this->mediumService->getAll();
            return $this->successResponse($data, 'Mediums retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve mediums', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MediumRequest $request)
    {
        $data = $request->validated();

        try {
            $resource = $this->mediumService->create($data);
            return $this->successResponse($resource, 'Medium created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create medium', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->mediumService->getById($id);
            return $this->successResponse($data, 'Medium retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Medium not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MediumRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $resource = $this->mediumService->update($id, $data);
            return $this->successResponse($resource, 'Medium updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update medium', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->mediumService->delete($id);
            return $this->successResponse(null, 'Medium deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete medium', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
