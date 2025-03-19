<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstituteRequest;
use App\Http\Requests\UpdateInstituteRequest;
use App\Services\InstituteService;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InstituteController extends Controller
{
    use HelperTrait;

    private $service;

    public function __construct(InstituteService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->service->index($request);

            return $this->successResponse($data, 'Institute data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstituteRequest $request): JsonResponse
    {
        try {
            $resource = $this->service->store($request);

            return $this->successResponse($resource, 'Institute created successfully!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to create Institute', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $resource = $this->service->show($id);

            return $this->successResponse($resource, 'Institute retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve Institute', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstituteRequest $request, int $id): JsonResponse
    {
        try {
            $resource = $this->service->update($request, $id);

            return $this->successResponse($resource, 'Institute updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update Institute', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->destroy($id);

            return $this->successResponse(null, 'Institute deleted successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to delete Institute', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

  public  function institutionList(Request $request): JsonResponse
  {
      try {
          $data = $this->service->institutionList();
          return $this->successResponse($data,'Institute data retrieved successfully!', Response::HTTP_OK);
      } catch (\Throwable $th) {
        return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
      }
  }

}
