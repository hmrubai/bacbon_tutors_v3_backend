<?php

namespace App\Http\Controllers;

use App\Services\JobApplicationService;
use App\Http\Requests\JobApplyRequest;
use App\Models\AppliedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Traits\HelperTrait;

class JobApplicationController extends Controller
{

    use HelperTrait;

    protected $jobApplicationService;

    public function __construct(JobApplicationService $service)
    {
        $this->jobApplicationService = $service;
    }

    /**
     * List all applied jobs for the authenticated tutor.
     */
    public function index(Request $request)
    {
        try {
            $tutorId = Auth::id();
            $data = $this->jobApplicationService->getByTutorId($tutorId);
            return $this->successResponse($data, 'Applied jobs retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve applied jobs', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a new job application.
     */
    public function store(JobApplyRequest $request)
    {
        try {
            $data = $request->all();
            $data['tutor_id'] = Auth::id(); // assume logged-in tutor is applying

            $appliedJob = $this->jobApplicationService->apply($data);

            return $this->successResponse($appliedJob, 'Job applied successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to apply for job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show details of a specific application.
     */
    public function show($id)
    {
        try {
            $data = $this->jobApplicationService->getById($id);
            return $this->successResponse($data, 'Applied job retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve applied job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete an application.
     */
    public function destroy($id)
    {
        try {
            $this->jobApplicationService->delete($id);
            return $this->successResponse(null, 'Application deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete application', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
