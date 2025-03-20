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
        return TutorJob::with(['medium', 'subjects', 'kid','grade'])
            ->where('user_id', $userId)
            ->get();
    }

    public function getById($id)
    {
        $tutorJob = TutorJob::with(['medium', 'subjects','institutes', 'kid','grade'])->findOrFail($id);

        return $tutorJob;
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['job_id'] = strtoupper("BT" . date('Ydm') . Str::random(6));

        $tutorJob = TutorJob::create($data);
        $tutorJob->subjects()->sync($data['subject_ids']);
        $tutorJob->institutes()->sync($data['institute_ids']);
        

        return $tutorJob;
    }

    public function update($id, array $data)
    {
        $job = TutorJob::findOrFail($id);
        unset($data['user_id']);
        $job->update($data)
            ->subjects()->sync($data['subject_ids'])
            ->institutes()->sync($data['institute_ids']);
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
    $query->with(['medium', 'subjects', 'kid', 'institutes','grade']);  // Add 'institutes' here
    $query->select(['*']);

    // Sorting
    $this->applySorting($query, $request);

    // Searching
    $searchKeys = ['job_title'];
    $this->applySearch($query, $request->input('search'), $searchKeys);

    // Filters (single-value filters)
    $filters = [
        'tuition_type',
        'division_id',
        'district_id',
        'upazila_id',
        'gender',
        'negotiable',
    ];

    foreach ($filters as $filter) {
        if ($request->has($filter)) {
            $query->where($filter, $request->input($filter));
        }
    }

    // Multi-value filters
    $multiValueFilters = [
        'area_ids' => 'area_id',
        'medium_ids' => 'medium_id',
        'class_ids' => 'grade_id',
        'institute_ids' => 'institutes', // Filter by related institutes
        'subject_ids' => 'subjects', // Filter by related subjects
    ];

    foreach ($multiValueFilters as $requestParam => $relation) {
        if ($request->has($requestParam)) {
            $values = explode(',', $request->input($requestParam));
            if ($relation === 'institutes') {
                // Filter by related institutes (belongsToMany)
                $query->whereHas('institutes', function ($query) use ($values) {
                    $query->whereIn('institute_id', $values);
                });
            } elseif ($relation === 'subjects') {
                // Filter by related subjects (belongsToMany)
                $query->whereHas('subjects', function ($query) use ($values) {
                    $query->whereIn('subject_id', $values);
                });
            } else {
                // Handle other cases for direct columns or special cases
                $query->whereIn($relation, $values);
            }
        }
    }

    // Salary range filter
    if ($request->has('salary_amount')) {
        $salaryRange = explode(',', $request->input('salary_amount'));
        if (count($salaryRange) === 2) {
            $query->whereBetween('salary_amount', [$salaryRange[0], $salaryRange[1]]);
        }
    }

    // Pagination
    return $this->paginateOrGet($query, $request);
}



    public function jobDetailsByID($id)
    {
        return TutorJob::with(['medium', 'subjects', 'kid'])->findOrFail($id);
    }
}
