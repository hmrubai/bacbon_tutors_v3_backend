<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\TutionAreaService;
use App\Http\Requests\TutionAreaRequest;

class TutionAreaController extends Controller
{
    use HelperTrait;

    protected $tutionAreaService;

    public function __construct(TutionAreaService $service)
    {
        $this->tutionAreaService = $service;
    }

    /**
     * List all tution area records for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        try {
            $areas = $this->tutionAreaService->getByUserId($userId);
            return $this->successResponse($areas, 'Tution areas retrieved successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve tution areas', 500);
        }
    }

    /**
     * Create a new tution area record for the authenticated user.
     */
    public function store(TutionAreaRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        try {
            $record = $this->tutionAreaService->create($data);
            return $this->successResponse($record, 'Tution area created successfully!', 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create tution area', 500);
        }
    }

    /**
     * Update a specific tution area record.
     * The record id must be passed in the URL.
     */
    public function update(TutionAreaRequest $request, $id): JsonResponse
    {
        $userId = Auth::id();
        $record = $this->tutionAreaService->getById($id);
        // Ensure the record belongs to the authenticated user.
        if ($record->user_id != $userId) {
            return $this->errorResponse('Unauthorized', 'You do not have permission to update this record', 403);
        }
        $data = $request->validated();
        try {
            $updated = $this->tutionAreaService->update($id, $data);
            return $this->successResponse($updated, 'Tution area updated successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update tution area', 500);
        }
    }

    /**
     * Delete a specific tution area record.
     * The record id must be passed in the URL.
     */
    public function destroy($id): JsonResponse
    {
        $userId = Auth::id();
        $record = $this->tutionAreaService->getById($id);
        // Ensure the record belongs to the authenticated user.
        if ($record->user_id != $userId) {
            return $this->errorResponse('Unauthorized', 'You do not have permission to delete this record', 403);
        }
        try {
            $result = $this->tutionAreaService->delete($id);
            return $this->successResponse($result, 'Tution area deleted successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete tution area', 500);
        }
    }

    //Admin endpoint: List all tution area records for a specific user by user_id.
   public function listByUser(Request $request, $user_id): JsonResponse
   {
       try {
           $areas = $this->tutionAreaService->getByUserId($user_id);
           return $this->successResponse($areas, "Tution areas for user_id: {$user_id} retrieved successfully!");
       } catch (\Throwable $th) {
           return $this->errorResponse($th->getMessage(), 'Failed to retrieve tution areas for the specified user', 500);
       }
   }
}
