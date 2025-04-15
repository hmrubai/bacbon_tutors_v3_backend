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
use JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class TutorJobService
{
    use HelperTrait;
    protected $appends = ['institutes'];

    public function getByUserId($userId)
    {
        return TutorJob::with(['medium', 'subjects', 'kid', 'grade', 'institutes', 'division', 'district', 'upazila', 'area'])
            ->where('user_id', $userId)
            ->get();
    }

    public function getById($id)
    {
        $tutorJob = TutorJob::with(['medium', 'subjects', 'institutes', 'kid', 'grade', 'division', 'district', 'upazila', 'area'])->findOrFail($id);

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
        $job->update($data);
        $job->subjects()->sync($data['subject_ids']);
        $job->institutes()->sync($data['institute_ids']);
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
        $query->with(['medium', 'subjects', 'kid', 'institutes', 'grade','user:id,name,email,username,profile_image']);
        $query->select(['*']);

        // Add bookmark flag
        $query->selectRaw('CASE WHEN EXISTS (
            SELECT 1 
            FROM tuition_bookmarks 
            WHERE tutor_job_id = tutor_jobs.id 
            AND user_id = COALESCE(?, 0)
        ) THEN 1 ELSE 0 END AS is_bookmark', [auth()->id() ?? 0]);

        // Add applied flag
        $query->selectRaw('CASE WHEN EXISTS (
            SELECT 1 
            FROM applied_jobs 
            WHERE job_id = tutor_jobs.id 
            AND tutor_id = COALESCE(?, 0)
        ) THEN 1 ELSE 0 END AS is_applied', [auth()->id() ?? 0]);

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
        if ($request->this_week==1) {
            $query->whereBetween('tutor_jobs.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        // This month filter
        if ($request->this_month==1) {
            $query->whereBetween('tutor_jobs.created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
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
  
       $job = TutorJob::with(['medium', 'subjects', 'kid', 'institutes', 'grade', 'division', 'district', 'upazila', 'area','user:id,name,email,username,profile_image'])->findOrFail($id);
       $job->is_bookmark = $job->bookmarkedJobs()->where('user_id', auth()->id())->exists()??false;
       $job->is_applied = $job->appliedJobs()->where('tutor_id', auth()->id())->exists()??false;

       return $job;
    }

    public function bookmarkTutorJob($id)
    {
        $user = Auth::user();

        if ($user->bookmarkedJobs()->where('tutor_job_id', $id)->exists()) {
            $user->bookmarkedJobs()->detach($id);
            return ['message' => 'Job removed from bookmarks'];
        } else {
            $user->bookmarkedJobs()->attach($id);
            return ['message' => 'Job bookmarked successfully'];
        }
    }

    public function getBookmarkedJobs()
    {
        $user = Auth::user();
        $jobs = $user->bookmarkedJobs()
            ->selectRaw('tutor_jobs.*, CASE WHEN EXISTS (
            SELECT 1 
            FROM tuition_bookmarks 
            WHERE tutor_job_id = tutor_jobs.id 
            AND user_id = ?
        ) THEN 1 ELSE 0 END AS is_bookmark', [auth()->id()??1])
         ->selectRaw('CASE WHEN EXISTS (
            SELECT 1 
            FROM applied_jobs 
            WHERE job_id = tutor_jobs.id 
            AND tutor_id = ?
        ) THEN 1 ELSE 0 END AS is_applied', [auth()->id()??1])
            ->with(['medium', 'subjects', 'kid', 'institutes', 'grade'])->get();

        return $jobs;
    }
}
