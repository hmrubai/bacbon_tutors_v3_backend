<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\KidInformationService;
use App\Http\Requests\KidInformationRequest;

class GuardianController extends Controller
{
    use HelperTrait;

    protected $kidInfoService;

    public function __construct(KidInformationService $service)
    {
        $this->kidInfoService = $service;
    }

    /**
     * List all kid information records for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $data = $this->kidInfoService->getByUserId($userId);
            return $this->successResponse($data, 'Kid information retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve kid information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new kid information record.
     */
    public function store(KidInformationRequest $request)
    {
        $data = $request->validated();
        try {
            $resource = $this->kidInfoService->create($data);
            return $this->successResponse($resource, 'Kid information created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create kid information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show a specific kid information record.
     */
    public function show($id)
    {
        try {
            $data = $this->kidInfoService->getById($id);
            if ($data->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to view this record', Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse($data, 'Kid information retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Kid information not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update an existing kid information record.
     */
    public function update(KidInformationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $kidInfo = $this->kidInfoService->getById($id);
            if ($kidInfo->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to update this record', Response::HTTP_FORBIDDEN);
            }
            $updated = $this->kidInfoService->update($id, $data);
            return $this->successResponse($updated, 'Kid information updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update kid information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a kid information record.
     */
    public function destroy($id)
    {
        try {
            $kidInfo = $this->kidInfoService->getById($id);
            if ($kidInfo->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to delete this record', Response::HTTP_FORBIDDEN);
            }
            $result = $this->kidInfoService->delete($id);
            return $this->successResponse($result, 'Kid information deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete kid information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
