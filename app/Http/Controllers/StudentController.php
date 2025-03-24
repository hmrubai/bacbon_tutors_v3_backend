<?php

namespace App\Http\Controllers;

use App\Http\Traits\HelperTrait;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    use HelperTrait;

    private $student;
    private $userService;
    public function __construct( UserService $userService)
    {
        $this->userService = $userService;
    }
    public function studentList(Request $request)
    {
        try {
            $userType="Student";
            $data = $this->userService->userList($request, $userType);
            return $this->successResponse($data, 'Student data retrieved successfully!', Response::HTTP_OK);
        } catch (\Exception $e) {
             return $this->errorResponse($e->getMessage(), 'Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);


        }
    }
}
