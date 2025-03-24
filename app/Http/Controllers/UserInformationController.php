<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use App\Services\UserInformationService;
use App\Http\Requests\UpdateUserInformationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserInformationController extends Controller
{
    use HelperTrait;

    protected $userInfoService;

    public function __construct(UserInformationService $service)
    {
        $this->userInfoService = $service;
    }

    public function getCompleteUserProfile(Request $request, $userId)
    {
        try {
            // Retrieve basic user information.
            $userInfo = $this->userInfoService->showUser($userId);
            return $this->successResponse($userInfo, 'User profile retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve user profile', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function showUser(Request $request)
    {
        try {
            $userId = Auth::id();
            $data = $this->userInfoService->showUser($userId);
            return $this->successResponse($data, 'User information retrieved successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to retrieve user information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the current user's information.
     */
    public function update(UpdateUserInformationRequest $request)
    {
        try {
            $userId = Auth::id();
            $data = $this->userInfoService->update($request, $userId);
            return $this->successResponse($data, 'User information updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to update user information', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        try {
          
            $data = $this->fileUpload($request, 'file', 'users');
            $user = User::find(Auth::id());
            $user->profile_image = $data;
            $user->save();
            return $this->successResponse($data, 'Profile image updated successfully!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 'Failed to upload file', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
