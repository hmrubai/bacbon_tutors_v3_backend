<?php

namespace App\Services;

use App\Models\SubjectExpertise;
use App\Http\Traits\HelperTrait;

class SubjectExpertiseService
{
    use HelperTrait;

    public function getAll()
    {
        return SubjectExpertise::all();
    }

    // Get subject expertise records for a specific tutor (by user_id)
    public function getByTutorId($userId)
    {
        return SubjectExpertise::where('user_id', $userId)->get();
    }

    public function create($data)
    {
        return SubjectExpertise::create([
            'medium_id'  => $data['medium_id'],
            'grade_id'   => $data['grade_id'],
            'subject_id' => $data['subject_id'],
            'user_id'    => $data['user_id'],  // For tutors, this will be set from Auth; for admins, passed in request.
            'remarks'    => $data['remarks'] ?? null,
            // If 'status' is not provided, default to true.
            'status'     => isset($data['status']) ? $data['status'] : true,
        ]);
    }

    public function getById($id)
    {
        return SubjectExpertise::findOrFail($id);
    }

    public function update($id, $data)
    {
        $expertise = SubjectExpertise::findOrFail($id);
        $expertise->update($data);
        return $expertise;
    }

    public function delete($id)
    {
        $expertise = SubjectExpertise::findOrFail($id);
        $expertise->delete();
        return ['message' => 'Deleted successfully'];
    }
}
