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
        $history->update([
            'title' => $data['title'],
            'institute' => $data['institute'],
            'discipline' => $data['discipline'] ?? null,
            'passing_year' => $data['passing_year'] ?? 0,
            'sequence' => $data['sequence'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
        return $history;
    }

    public function delete($id)
    {
        $history = TutorEducationHistory::findOrFail($id);
        $history->delete();
        return ['message' => 'Deleted successfully'];
    }

    public function allTutorList($request)
    {
        $query = User::query()
            ->where('users.user_type', 'Teacher')
            ->where('users.is_active', 1)
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.username',
                'users.department',
                'users.profile_image',
                'users.subject',
            ])
            ->leftJoin('subject_expertise', 'users.id', '=', 'subject_expertise.user_id')
            ->leftJoin('tution_areas', 'users.id', '=', 'tution_areas.user_id');

        $this->applyFilters($query, $request, [
            'gender'       => '=',
            'institute_id' => '=',
        ]);

        $this->applyWhereIn($query, 'subject_expertise.medium_id', $request->medium_ids);
        $this->applyWhereIn($query, 'subject_expertise.grade_id', $request->grade_ids);
        $this->applyWhereIn($query, 'subject_expertise.subject_id', $request->subject_ids);

        $query->when($request->tuition_type, fn($q, $val) => $q->where('subject_expertise.tuition_type', $val))
            ->when($request->rate, fn($q, $val) => $q->where('subject_expertise.rate', $val));

        if ($request->filled('fee')) {
            $fees = explode(',', $request->fee);
            $minFee = $fees[0] ?? null;
            $maxFee = $fees[1] ?? null;

            if ($minFee !== null) {
                $query->where('subject_expertise.fee', '>=', $minFee);
            }

            if ($maxFee !== null) {
                $query->where('subject_expertise.fee', '<=', $maxFee);
            }
        }

        foreach (['division_id', 'district_id', 'upazila_id', 'union_id'] as $filter) {
            $query->when($request->$filter, fn($q, $val) => $q->where("tution_areas.$filter", $val));
        }

        $this->applySearch($query, $request->input('search'), [
            'name',
            'email',
            'username',
            'primary_number',
        ]);
        $this->applySorting($query, $request);

        $query->groupBy([
            'users.id',
            'users.name',
            'users.email',
            'users.username',
            'users.department',
            'users.profile_image',
            'users.subject',
        ]);

        return $this->paginateOrGet($query, $request);
    }
}
