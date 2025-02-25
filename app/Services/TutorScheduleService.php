<?php

namespace App\Services;

use App\Models\TutorSchedule;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorScheduleService
{
    use HelperTrait;

    /**
     * Get all schedule records for the given user.
     */
    public function getByUserId($userId)
    {
        return TutorSchedule::where('user_id', $userId)->get();
    }

    /**
     * Get a specific schedule record by its ID.
     */
    public function getById($id)
    {
        return TutorSchedule::findOrFail($id);
    }

    /**
     * Create a new tutor schedule record.
     */
    public function create(array $data)
    {
        // Ensure the record is associated with the authenticated user.
        $data['user_id'] = Auth::id();
        return TutorSchedule::create($data);
    }

    /**
     * Update an existing schedule record.
     */
    public function update($id, array $data)
    {
        $record = TutorSchedule::findOrFail($id);
        $record->update($data);
        return $record;
    }

    /**
     * Delete a schedule record.
     */
    public function delete($id)
    {
        $record = TutorSchedule::findOrFail($id);
        $record->delete();
        return ['message' => 'Deleted successfully'];
    }
}
