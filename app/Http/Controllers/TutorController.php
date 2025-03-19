<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\TutorInformationService;
use App\Http\Requests\TutorEducationRequest;
use App\Services\UserService;

class TutorController extends Controller
{
    use HelperTrait;

    protected $tp_service;
    protected $userService;

    public function __construct(TutorInformationService $service,
    UserService $userService
    )
    {
        $this->tp_service = $service;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->tp_service->getAll();

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    // Only Toturs
    public function getTutorEducationHistory(Request $request)
    {
        $tutor_id = Auth::id();
        if(!$tutor_id){
            return $this->errorResponse("Please Attach Tutor ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);  
        }

        try {
            $data = $this->tp_service->getEducationListByTutorID($tutor_id);

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // For Admins and System Admins
    public function tutorEducationForAdmin(Request $request, $tutor_id)
    {
        $tutor_id = $tutor_id ? $tutor_id : 0;
        if(!$tutor_id){
            return $this->errorResponse("Please Attach Tutor ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);  
        }

        try {
            $data = $this->tp_service->getEducationListByTutorID($tutor_id);

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeEducationByUser(TutorEducationRequest $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'institute' => 'required|string',
            'discipline' => 'nullable|string',
            'passing_year' => 'nullable|string',
            'sequence' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data['created_by'] = Auth::id();
        $data['user_id'] = Auth::id();

        try {
            $resource = $this->tp_service->create($data);

            return $this->successResponse($resource, 'Education Information added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create Information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeEducationByAdmin(TutorEducationRequest $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            'title' => 'required|string',
            'institute' => 'required|string',
            'discipline' => 'nullable|string',
            'passing_year' => 'nullable|string',
            'sequence' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data['created_by'] = Auth::id();

        try {
            $resource = $this->tp_service->create($data);

            return $this->successResponse($resource, 'Education Information added successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create Information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->tp_service->getById($id);

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateEducationHistories(TutorEducationRequest $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'institute' => 'required|string',
            'discipline' => 'nullable|string',
            'passing_year' => 'nullable|string',
            'sequence' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        try {
            $resource = $this->tp_service->update($id, $data);

            return $this->successResponse($resource, 'Education Information updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update Tutor Education Information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->tp_service->update($id, $data));
    }

    public function deleteEducationHistories($id)
    {
        try {
            $this->tp_service->delete($id);

            return $this->successResponse(null, 'Education Informatio deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete Tutor Education Information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allTutorList(Request $request)
    {
        try {
            $data = $this->tp_service->allTutorList($request);

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tutorDetails(Request $request, $id)
    {
        try {
            $data = $this->userService->tutorDetails($request,$id);

            return $this->successResponse($data, 'Tutor data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
