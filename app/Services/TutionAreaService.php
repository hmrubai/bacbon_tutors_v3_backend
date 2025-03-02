<?php

namespace App\Services;

use App\Models\TutionArea;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;

class TutionAreaService
{
    use HelperTrait;

    // List all tution area records for a given user.
    public function getByUserId($userId)
    {
        return TutionArea::where('user_id', $userId)->get();
    }

    public function create(array $data)
    {
        // Ensure the record is associated with the authenticated user.
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        return TutionArea::create($data);
    }

    public function getById($id)
    {
        return TutionArea::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $record = TutionArea::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = TutionArea::findOrFail($id);
        $record->delete();
        return ['message' => 'Deleted successfully'];
    }
}
