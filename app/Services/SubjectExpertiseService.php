<?php

namespace App\Services;

use App\Models\SubjectExpertise;
use App\Http\Traits\HelperTrait;

class SubjectExpertiseService
{
    use HelperTrait;

    // Get all records with related medium, grade, and subject data
    public function getAll()
    {
        return SubjectExpertise::with(['medium', 'grade', 'subject'])->get();
    }

    // Get subject expertise records for a specific tutor (by user_id) with relationships
    public function getByTutorId($userId)
    {
        return SubjectExpertise::with(['medium', 'grade', 'subject'])
            ->where('user_id', $userId)
            ->get();
    }

    public function create($data)
    {
        return SubjectExpertise::create([
            'medium_id'  => $data['medium_id'],
            'grade_id'   => $data['grade_id'],
            'subject_id' => $data['subject_id'],
            'user_id'    => $data['user_id'],
            'remarks'    => $data['remarks'] ?? null,
            'status'     => isset($data['status']) ? $data['status'] : true,
        ]);
    }

    public function getById($id)
    {
        return SubjectExpertise::with(['medium', 'grade', 'subject'])->findOrFail($id);
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
