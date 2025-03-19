<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\TutorScheduleService;
use App\Http\Requests\TutorScheduleRequest;

class TutorScheduleController extends Controller
{
    use HelperTrait;

    protected $tutorScheduleService;

    public function __construct(TutorScheduleService $service)
    {
        $this->tutorScheduleService = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        try {
            $schedules = $this->tutorScheduleService->getByUserId($userId);
            return $this->successResponse($schedules, 'Tutor schedules retrieved successfully!', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve tutor schedules', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function store(TutorScheduleRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $schedule = $this->tutorScheduleService->create($data);
            return $this->successResponse($schedule, 'Tutor schedule created successfully!', 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create tutor schedule', 500);
        }
    }

    public function createOrUpdate(TutorScheduleRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $schedule = $this->tutorScheduleService->createOrUpdate($data);
            return $this->successResponse($schedule, 'Tutor schedule has been updated successfully!', 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create tutor schedule', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $schedule = $this->tutorScheduleService->getById($id);
            // Ensure the schedule belongs to the authenticated user
            if ($schedule->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to view this schedule', 403);
            }
            return $this->successResponse($schedule, 'Tutor schedule retrieved successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve tutor schedule', 500);
        }
    }

    public function update(TutorScheduleRequest $request, $id): JsonResponse
    {
        $userId = Auth::id();
        try {
            $schedule = $this->tutorScheduleService->getById($id);
            if ($schedule->user_id != $userId) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to update this schedule', 403);
            }
            $data = $request->validated();
            $updated = $this->tutorScheduleService->update($id, $data);
            return $this->successResponse($updated, 'Tutor schedule updated successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update tutor schedule', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $userId = Auth::id();
        try {
            $schedule = $this->tutorScheduleService->getById($id);
            if ($schedule->user_id != $userId) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to delete this schedule', 403);
            }
            $result = $this->tutorScheduleService->delete($id);
            return $this->successResponse($result, 'Tutor schedule deleted successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete tutor schedule', 500);
        }
    }

    public function listByUser(Request $request, $user_id): JsonResponse
    {
        try {
            $schedules = $this->tutorScheduleService->getByUserId($user_id);
            return $this->successResponse($schedules, "Tutor schedules for user_id: {$user_id} retrieved successfully!");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), "Failed to retrieve tutor schedules for user_id: {$user_id}", 500);
        }
    }
}
