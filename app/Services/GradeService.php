<?php

namespace App\Services;

use App\Models\Grade;
use App\Http\Traits\HelperTrait;

class GradeService
{
    use HelperTrait;

    public function getAll()
    {
        return Grade::all();
    }

    public function create(array $data)
    {
        return Grade::create($data);
    }

    public function getById($id)
    {
        return Grade::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $grade = Grade::findOrFail($id);
        $grade->update($data);
        return $grade;
    }
    public function getGradesByMediumId($mediumId)
    {
        return Grade::where('medium_id', $mediumId)->get();
    }

    public function getGradesByMedium($request)
    {
        $mediumIds = explode(',', $request->medium_ids);
        return Grade::whereIn('medium_id', $mediumIds)->get();

        
    }
    
    public function delete($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return ['message' => 'Deleted successfully'];
    }
}
