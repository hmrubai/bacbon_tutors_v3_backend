<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Services\AddressService;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    use HelperTrait;

    protected $addressService;

    public function __construct(AddressService $service)
    {
        $this->addressService = $service;
    }
    // Show address for a given user_id (admin view)
    public function showByUser(Request $request, $user_id)
    {
        try {
            $address = $this->addressService->getByUserId($user_id);
            if (!$address) {
                return $this->errorResponse("Address not found", "No address found for the given user ID", Response::HTTP_NOT_FOUND, false);
            }
            return $this->successResponse($address, "Address retrieved successfully for user ID: {$user_id}", Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), "Failed to retrieve address", Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Create a new address (tutors only)
    public function store(AddressRequest $request)
    {
        $userId = Auth::id();
        // Check if an address already exists for this user
        $existing = $this->addressService->getByUserId($userId);
        if ($existing) {
            return $this->errorResponse("Address already exists", "Address already exists. Use update to modify it.", Response::HTTP_UNPROCESSABLE_ENTITY, false);
        }
        $data = $request->validated();
        $data['user_id'] = $userId;

        try {
            $address = $this->addressService->create($data);
            return $this->successResponse($address, "Address created successfully!", Response::HTTP_CREATED, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), "Failed to create address", Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }

    // Show the address for the logged-in tutor
    public function show(Request $request)
    {
        $userId = Auth::id();
        $address = $this->addressService->getByUserId($userId);
        if (!$address) {
            return $this->errorResponse("Address not found", "No address found for the user", Response::HTTP_NOT_FOUND, false);
        }
        return $this->successResponse($address, "Address retrieved successfully!", Response::HTTP_OK, true);
    }

    // Update the address for the logged-in tutor
    public function update(AddressRequest $request)
    {
        $userId = Auth::id();
        $address = $this->addressService->getByUserId($userId);
        if (!$address) {
            return $this->errorResponse("Address not found", "No address exists to update. Create one first.", Response::HTTP_NOT_FOUND, false);
        }
        $data = $request->validated();
        try {
            $updated = $this->addressService->update($userId, $data);
            return $this->successResponse($updated, "Address updated successfully!", Response::HTTP_OK, true);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), "Failed to update address", Response::HTTP_INTERNAL_SERVER_ERROR, false);
        }
    }
}
