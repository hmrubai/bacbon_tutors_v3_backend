<?php

namespace App\Http\Controllers;

use App\Models\Union;
use App\Models\Upazila;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\LocationService;

class LocationController extends Controller
{
    use HelperTrait;

    protected $ls_service;

    public function __construct(LocationService $service)
    {
        $this->ls_service = $service;
    }

    public function divisionList(Request $request)
    {
        try {
            $data = $this->ls_service->divisionList();

            return $this->successResponse($data, 'Division data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function districtListByID(Request $request, $division_id = 0)
    {
        if(!$division_id){
            return $this->errorResponse("Please Attach Division ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);  
        }
        try {
            $data = $this->ls_service->districtListByID($division_id);

            return $this->successResponse($data, 'District data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function upazilaListByID(Request $request, $district_id = 0)
    {
        if(!$district_id){
            return $this->errorResponse("Please Attach District ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);  
        }
        try {
            $data = $this->ls_service->upazilaListByID($district_id);

            return $this->successResponse($data, 'Upazila data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unionListByID(Request $request, $upazila_id = 0)
    {
        if(!$upazila_id){
            return $this->errorResponse("Please Attach Upazilla ID", 'Something went wrong', Response::HTTP_UNPROCESSABLE_ENTITY);  
        }
        try {
            $data = $this->ls_service->unionListByID($upazila_id);

            return $this->successResponse($data, 'Area data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
