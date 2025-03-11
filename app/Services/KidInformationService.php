<?php

namespace App\Services;

use App\Models\KidInformation;
use App\Http\Traits\HelperTrait;

class KidInformationService
{
    use HelperTrait;

    /**
     * List all kid information records for the current user.
     */
    public function getByUserId($userId)
    {
        return KidInformation::with('grade')->where('user_id', $userId)->get();
    }

    public function getById($id)
    {
        return KidInformation::with('grade')->findOrFail($id);
    }

    /**
     * Create a new kid information record.
     */
    public function create(array $data)
    {
        // Assume the authenticated user is the owner.
        $data['user_id'] = auth()->id();
        return KidInformation::create($data);
    }

    /**
     * Update an existing kid information record.
     */
    public function update($id, array $data)
    {
        $kidInfo = KidInformation::findOrFail($id);
        $kidInfo->update($data);
        return $kidInfo;
    }

    /**
     * Delete a kid information record.
     */
    public function delete($id)
    {
        $kidInfo = KidInformation::findOrFail($id);
        $kidInfo->delete();
        return ['message' => 'Deleted successfully'];
    }
}
