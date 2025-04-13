<?php

namespace App\Http\Controllers;

use App\Services\JobApplicationService;
use App\Http\Requests\JobApplyRequest;
use App\Models\AppliedJob;
use App\Models\User;
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

    public function index(Request $request)
    {
        try {
            $tutorId = Auth::id();
            $data = $this->jobApplicationService->getByTutorId($request,$tutorId);
            return $this->successResponse($data, 'Applied jobs retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve applied jobs', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function applyForAJob(JobApplyRequest $request)
    {
        $tutor_id =  Auth::id();
        $job_id =  $request->job_id;

        if ($this->isTutor($tutor_id)) {
            return $this->errorResponse([], 'Become a Tutor to apply!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($this->isJobAlreadyApplied($job_id, $tutor_id)) {
            return $this->errorResponse([], 'You have already applied for this job.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $request->all();
            $data['tutor_id'] = Auth::id();

            $appliedJob = $this->jobApplicationService->apply($data);

            return $this->successResponse($appliedJob, 'Job applied successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to apply for job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->jobApplicationService->getById($id);
            return $this->successResponse($data, 'Applied job retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve applied job', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $this->jobApplicationService->delete($id);
            return $this->successResponse(null, 'Application deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete application', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function isJobAlreadyApplied($job_id, $tutor_id)
    {
        return AppliedJob::where('tutor_id', $tutor_id)->where('job_id', $job_id)->get()->count() ? true : false;
    }

    public function isTutor($tutor_id)
    {
        return User::where('id', $tutor_id)->where('user_type', "Teacher")->get()->count() ? false : true;
    }

    public function hireTutorList(Request $request)
    {
        $tutor_id = Auth::id();
        $data = $this->jobApplicationService->hireTutorList( $request);
        return $this->successResponse($data, 'Applied jobs retrieved successfully!', Response::HTTP_OK);
    }
}
