<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Http\Traits\HelperTrait;
use App\Models\AppliedJob;
use Illuminate\Support\Facades\Auth;
use App\Models\TutorEducationHistory;
use App\Models\TutorJob;
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
            'is_online'    => '=',
        ]);

        $this->applyWhereIn($query, 'subject_expertise.medium_id', $request->medium_ids);
        $this->applyWhereIn($query, 'subject_expertise.grade_id', $request->grade_ids);
        $this->applyWhereIn($query, 'subject_expertise.subject_id', $request->subject_ids);

        $query->when($request->rate, fn($q, $val) => $q->where('subject_expertise.rate', $val));

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

    public function myTutorList(Request $request): Collection|LengthAwarePaginator|array
    {
        $query = User::query();

        // Select specific columns
        $query->select([
            'users.id',
            'users.name',
            'users.email',
            'users.username',
            'users.department',
            'users.profile_image',
            'users.subject'
        ]);
        $query->where('users.user_type', 'Teacher');
        $query->selectRaw('COALESCE(ROUND(RAND() * 4 + 1, 1), 0) as review');

        $query->leftJoin('applied_jobs as aj', 'users.id', '=', 'aj.tutor_id');
        $query->where('aj.is_linked_up', 1);
        $query->where('aj.linked_up_with_id', Auth::user()->id);

        // Sorting
        $this->applySorting($query, $request);

        // Searching
        $searchKeys = ['name', 'email', 'username']; // Define the fields you want to search by
        $this->applySearch($query, $request->input('search'), $searchKeys);


        // Pagination
        return $this->paginateOrGet($query, $request);
    }

    public function hireTutor(Request $request, $id)
    {
        $tutor = AppliedJob::findOrFail($id);
        $studentId = TutorJob::where('id', $tutor->job_id)->first();
        $tutor->update([
            'is_linked_up' => 1,
            'linked_up_with_id' => $studentId->user_id,
            'status' => "accepted",
        ]);
        return $tutor;
    }


    public function favoriteTutor(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->favoriteTutors()->where('tutor_id', $id)->exists()) {
            $user->favoriteTutors()->detach($id);
            return ['message' => 'Tutor unfavorited successfully', 'is_favorite' => false];
        } else {
            $user->favoriteTutors()->attach($id);
            return  ['message' => 'Tutor favorited successfully', 'is_favorite' => true];
        }
    }


    public function getFavoriteJobs()
    {
        $user = Auth::user();
        $tutor = $user->favoriteTutors()->get();

        $tutor = $tutor->map(function ($item) {
            $item->review = rand(10, 50) / 10;
            $item->favorite = true;
            return $item;
        });

        return $tutor;
    }

    public function updateOnlineStatus(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'is_online' => !$user->is_online,
        ]);
        return $user;
    }
}
