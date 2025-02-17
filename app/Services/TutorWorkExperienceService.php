<?php

namespace App\Services;

use App\Models\TutorWorkExperience;
use App\Http\Traits\HelperTrait;

class TutorWorkExperienceService
{
    use HelperTrait;

    public function getAll()
    {
        return TutorWorkExperience::all();
    }

    // Get work experiences for a specific tutor (by user_id)
    public function getByTutorId($userId)
    {
        return TutorWorkExperience::where('user_id', $userId)
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function create($data)
    {
        return TutorWorkExperience::create([
            'user_id'         => $data['user_id'],
            'employment_type' => $data['employment_type'],
            'designation'     => $data['designation'],
            'company_name'    => $data['company_name'],
            'currently_working'=> $data['currently_working'] ?? false,
            'start_date'      => $data['start_date'],
            'end_date'        => $data['end_date'] ?? null,
        ]);
    }

    public function getById($id)
    {
        return TutorWorkExperience::findOrFail($id);
    }

    public function update($id, $data)
    {
        $experience = TutorWorkExperience::findOrFail($id);
        $experience->update($data);
        return $experience;
    }

    public function delete($id)
    {
        $experience = TutorWorkExperience::findOrFail($id);
        $experience->delete();
        return ['message' => 'Deleted successfully'];
    }
}
