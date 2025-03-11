<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\TutorJobService;
use App\Http\Requests\JobRequest;

class TutorJobController extends Controller
{
    use HelperTrait;

    protected $jobService;

    public function __construct(TutorJobService $service)
    {
        $this->jobService = $service;
    }

    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $data = $this->jobService->getByUserId($userId);
            return $this->successResponse($data, 'Jobs retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve jobs', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(JobRequest $request)
    {
        $data = $request->validated();
        try {
            $resource = $this->jobService->create($data);
            return $this->successResponse($resource, 'Job created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $job = $this->jobService->getById($id);
            if ($job->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to view this job', Response::HTTP_FORBIDDEN);
            }
            return $this->successResponse($job, 'Job retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Job not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(JobRequest $request, $id)
    {
        try {
            $job = $this->jobService->getById($id);
            if ($job->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to update this job', Response::HTTP_FORBIDDEN);
            }
            $data = $request->validated();
            $updated = $this->jobService->update($id, $data);
            return $this->successResponse($updated, 'Job updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $job = $this->jobService->getById($id);
            if ($job->user_id != Auth::id()) {
                return $this->errorResponse('Unauthorized', 'You do not have permission to delete this job', Response::HTTP_FORBIDDEN);
            }
            $result = $this->jobService->delete($id);
            return $this->successResponse($result, 'Job deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Open API for all users
    public function allJobList(Request $request)
    {
        try {
            $data = $this->jobService->allJobs();
            return $this->successResponse($data, 'Jobs retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve jobs', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function jobDetails($id)
    {
        try {
            $job = $this->jobService->jobDetailsByID($id);
            return $this->successResponse($job, 'Job retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Job not found', Response::HTTP_NOT_FOUND);
        }
    }
}
