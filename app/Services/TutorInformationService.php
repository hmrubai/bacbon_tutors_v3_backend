<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\TutorEducationHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TutorInformationService
{
    use HelperTrait;


    public function getAll()
    {
        return TutorEducationHistory::all();
    }

    public function getEducationListByTutorID($id)
    {
        return TutorEducationHistory::where('user_id', $id)->orderby('sequence', "ASC")->get();
    }

    public function create($data)
    {
        return TutorEducationHistory::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'institute' => $data['institute'],
            'discipline' => $data['discipline'] ?? null,
            'passing_year' => $data['passing_year'] ?? 0,
            'sequence' => $data['sequence'] ?? 0,
            'created_by' => $data['created_by'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function getById($id)
    {
        return TutorEducationHistory::findOrFail($id);
    }

    public function update($id, $data)
    {
        $history = TutorEducationHistory::findOrFail($id);
        $history->update($data);
        return $history;
    }

    public function delete($id)
    {
        $history = TutorEducationHistory::findOrFail($id);
        $history->delete();
        return ['message' => 'Deleted successfully'];
    }
}
