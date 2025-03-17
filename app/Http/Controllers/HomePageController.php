<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
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
}
