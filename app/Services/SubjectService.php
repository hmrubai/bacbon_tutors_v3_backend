<?php

namespace App\Services;

use App\Models\Subject;
use App\Http\Traits\HelperTrait;

class SubjectService
{
    use HelperTrait;

    public function getAll()
    {
        return Subject::all();
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function getById($id)
    {
        return Subject::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($data);
        return $subject;
    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return ['message' => 'Deleted successfully'];
    }
    // New method to get subjects by medium_id
    public function getSubjectsByMediumId($mediumId)
    {
        return Subject::where('medium_id', $mediumId)->get();
    }

    //Get subjects by medium_id & Grade ID
    public function getSubjectsByMediumGradeId($mediumId, $gradeId)
    {
        return Subject::where('medium_id', $mediumId)->where('grade_id', $gradeId)
            ->get();
    }
}
