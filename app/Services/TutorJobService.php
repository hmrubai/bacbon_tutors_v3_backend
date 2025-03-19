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
        $data['job_id'] = strtoupper("BT" . date('Ydm') . Str::random(6));
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
            'subject_ids' => 'subject_id',
            'institute_ids' => 'institute_ids',
            // 'techar_ids' => 'techar_ids', // Added techar_ids here
        ];

        // Columns that require special handling (e.g., FIND_IN_SET)
        $specialColumns = [
            'institute_ids'
            // , 'techar_ids'
        ];

        foreach ($multiValueFilters as $requestParam => $column) {
            if ($request->has($requestParam)) {
                $values = explode(',', $request->input($requestParam));
                if (in_array($column, $specialColumns)) {
                    // Special handling for FIND_IN_SET query
                    $query->where(function ($subQuery) use ($values, $column) {
                        foreach ($values as $value) {
                            $subQuery->orWhereRaw("FIND_IN_SET(?, {$column})", [$value]);
                        }
                    });
                } else {
                    $query->whereIn($column, $values);
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
        return TutorJob::with(['medium', 'subject', 'kid'])->findOrFail($id);
    }
}
