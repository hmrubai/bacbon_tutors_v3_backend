<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeCarousel;
use App\Services\HomePageService;

class HomePageController extends Controller
{
    use HelperTrait;

    protected $HPservice;

    public function __construct(HomePageService $service)
    {
        $this->HPservice = $service;
    }

    public function homePageDetails(Request $request)
    {
        try {
            $data = $this->HPservice->homePageDetailsByUser($request);

            return $this->successResponse($data, 'Data retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function studentDashboard(Request $request)
    {
        try {
            $data = $this->HPservice->studentDashboard($request);
            return $this->successResponse($data,'Data retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function allCarousel()
    {
        try {
            $data = $this->HPservice->getAllCarousel();
            return $this->successResponse($data, 'All carousel items fetched.', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to fetch data.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function carouselDetails($id)
    {
        try {
            $data = $this->HPservice->getCarouselById($id);
            return $this->successResponse($data, 'Carousel item retrieved.', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Item not found.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeCarousel(Request $request)
    {
        try {
            $data = $request->validate([
                'title'           => 'required|string',
                'description'     => 'nullable|string',
                'thumbnail'       => 'nullable|string',
                'background_color'=> 'nullable|string',
                'has_button'      => 'boolean',
                'button_text'     => 'nullable|string',
                'button_link'     => 'nullable|url',
                'sorting_order'   => 'integer',
                'is_active'       => 'boolean',
            ]);
            
            $carousel = $this->HPservice->createCarousel($data);
            return $this->successResponse($carousel, 'Carousel item created.', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Creation failed.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCarousel(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'title'           => 'sometimes|string',
                'description'     => 'nullable|string',
                'thumbnail'       => 'nullable|string',
                'background_color'=> 'nullable|string',
                'has_button'      => 'boolean',
                'button_text'     => 'nullable|string',
                'button_link'     => 'nullable|url',
                'sorting_order'   => 'integer',
                'is_active'       => 'boolean',
            ]);

            $carousel = $this->HPservice->updateCarousel($id, $data);
            return $this->successResponse($carousel, 'Carousel item updated.', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Update failed.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroyCarousel($id)
    {
        try {
            $this->HPservice->deleteCarousel($id);
            return $this->successResponse(null, 'Carousel item deleted.', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Deletion failed.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
