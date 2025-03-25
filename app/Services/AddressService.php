<?php

namespace App\Services;

use App\Models\Address;
use App\Http\Traits\HelperTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    use HelperTrait;

    // Retrieve the address for a specific user (assumes one record per user)
    public function getByUserId($userId)
    {
        return Address::where('user_id', $userId)->first();
    }

    // Create a new address record
    public function create($data)
    {
        return Address::create([
            'present_address'   => $data['present_address'],
            'permanent_address' => $data['permanent_address'] ?? null,
            'user_id'           => $data['user_id'],
        ]);
    }

    // Update the address record for a specific user
    public function update($userId, $data)
    {
        $address = $this->getByUserId($userId);
        if ($address) {
            $address->update($data);
            return $address;
        }
        return null;
    }

    public function addressUpdate($request)
    {

        $userId = Auth::id();
        $data = $request->validated();
        $user = User::findOrFail($userId);
        $user->update($data);

        return $user;
    }
}
