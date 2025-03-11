<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Traits\HelperTrait;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HelperTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;

    }

    public function checkUserVarification(LoginRequest $request){

        $user_type = $request->user_type ? $request->user_type : "Student";

        if(!$request->email_or_username){
            return response()->json([
                'data' => [],
                'message' => 'Please attach Email/Phone No!',
                'status' => false
            ], 422);
        }

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email_or_username)
                ->orWhere('username', $request->email_or_username);
            })
            ->where('user_type', $user_type)
            ->first();

        if ($user) {
            if($user->is_password_set){
                return response()->json([
                    'data' => [
                        'is_password_set' => true,
                    ],
                    'message' => 'Enter Password to login!',
                    'status' => true
                ], 200);
            }
        }

        try {
            $data = $this->authService->checkUserVarification($request);
            return response()->json([
                'data' => [
                    'is_password_set' => false,
                ],
                'message' => 'OTP sent successfully!',
                'status' => true
            ], 200);
            return $this->successResponse($data, 'OTP sent successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function forgetPassword(LoginRequest $request){

        $user_type = $request->user_type ? $request->user_type : "Student";

        if(!$request->email_or_username){
            return response()->json([
                'data' => [],
                'message' => 'Please attach Email/Phone No!',
                'status' => false
            ], 422);
        }

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email_or_username)
                ->orWhere('username', $request->email_or_username);
            })
            ->where('user_type', $user_type)
            ->first();

        if (!$user) {
            return response()->json([
                'data' => [],
                'message' => 'User not found!',
                'status' => false
            ], 422);
        }

        try {
            $data = $this->authService->checkUser($request);
            return response()->json([
                'data' => [
                    'is_password_set' => false,
                ],
                'message' => 'OTP sent successfully!',
                'status' => true
            ], 200);
            return $this->successResponse($data, 'OTP sent successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function checkOrCreateUser(LoginRequest $request)
    {
        $user_type = $request->user_type ? $request->user_type : "Student";

        if(!$request->email_or_username){
            return response()->json([
                'data' => [],
                'message' => 'Please attach Email/Phone No!',
                'status' => false
            ], 422);
        }

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email_or_username)
                ->orWhere('username', $request->email_or_username);
            })
            ->where('user_type', $user_type)
            ->first();

        if ($user) {
            if($user->is_password_set){
                return response()->json([
                    'data' => [
                        'is_password_set' => true,
                    ],
                    'message' => 'Enter Password to login!',
                    'status' => true
                ], 200);
            }
        }

        try {
            $data = $this->authService->checkUser($request);
            return response()->json([
                'data' => [
                    'is_password_set' => false,
                ],
                'message' => 'OTP sent successfully!',
                'status' => true
            ], 200);
            return $this->successResponse($data, 'OTP sent successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function verifyAndLogin(LoginRequest $request){
        try {
            $data = $this->authService->verifyOtpForLogin($request);
            return $this->successResponse($data, 'User logged in successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Register API - POST

    public function register(RegistrationRequest $request)
    {
        try {
            $user = $this->authService->register($request);

            return $this->successResponse($user, 'User registered successfully', Response::HTTP_CREATED, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }

    }

    public function sendOtp(Request $request){
        try {
            $this->authService->sendOtp($request);
            return $this->successResponse([], 'OTP sent successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Login API - POST

    public function login(LoginRequest $request)
    {
        try {

            $data = $this->authService->login($request);

            return $this->successResponse($data, 'User logged in successfully', Response::HTTP_OK, true);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Profile API - GET (JWT Auth Token)
    public function profile()
    {
        try {
            $user = $this->authService->profile();

            return $this->successResponse($user, 'User profile', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }

    }

    // Refresh Token API - GET (JWT Auth Token)
    public function refreshToken()
    {

        try {
            $data = $this->authService->refreshToken();

            return $this->successResponse($data, 'Token refreshed successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }

    }

    // Logout API - GET (JWT Auth Token)
    public function logout()
    {
        try {
            auth()->logout();

            return $this->successResponse(true, 'User logged out successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Change Password API - POST
     public function changePassword(ChangePasswordRequest $request)
    {
        try {

            $data = $this->authService->changePassword( $request );

            return $this->successResponse($data, 'Password changed successfully', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function details()
    {
        try {
            $menus = $this->authService->details();
            return $this->successResponse($menus, 'Menus', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6',
        ]);

        $user = Auth::user();

        try {
            $data = $this->authService->setPassword($user, $request->name, $request->new_password);
            return $this->successResponse($data, 'Password has been updated successfully', Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse([], $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

}
