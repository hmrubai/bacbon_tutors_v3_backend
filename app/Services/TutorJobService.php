<?php

namespace App\Services;
use App\Models\TutorJob;
use Illuminate\Support\Str;
use App\Http\Traits\HelperTrait;
use App\Models\Institute;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorJobService
{
    use HelperTrait;
    protected $appends = ['institutes'];

    public function getByUserId($userId)
    {
        return TutorJob::with(['medium', 'subject', 'kid'])
                  ->where('user_id', $userId)
                  ->get();
    }

    public function getById($id)
    {
        $tutorJob = TutorJob::with(['medium', 'subject', 'kid'])->findOrFail($id);

        $instituteIds = explode(',', $tutorJob->institute_ids);

        $institutes = Institute::whereIn('id', $instituteIds)->get();

        $tutorJob->institutes = $institutes;

        return $tutorJob;
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['job_id'] = strtoupper("BT".date('Ydm').Str::random(6));
        return TutorJob::create($data);
    }

    public function update($id, array $data)
    {
        $job = TutorJob::findOrFail($id);
        unset($data['user_id']);
        $job->update($data);
        return $job;
    }

    public function delete($id)
    {
        $job = TutorJob::findOrFail($id);
        $job->delete();
        return ['message' => 'Deleted successfully'];
    }


    public function allJobs(Request $request): Collection|LengthAwarePaginator|array
    {
        $query = TutorJob::query();
        $query->with(['medium', 'subject', 'kid']);

        $filters = ['job_id' => '=',];

        // Select specific columns
        $query->select(['*']);

        // Sorting
        $this->applySorting($query, $request);

        $this->applyFilters($query, $request, $filters);
        // Searching
        $searchKeys = ['job_title']; // Define the fields you want to search by
        $this->applySearch($query, $request->input('search'), $searchKeys);

        // Pagination
        return $this->paginateOrGet($query, $request);
    }

    public function jobDetailsByID($id)
    {
        return TutorJob::with(['medium', 'subject', 'kid'])->findOrFail($id);
    }
}
