<?php

namespace App\Services;

use App\Models\AppliedJob;
use App\Http\Traits\HelperTrait;
use App\Models\TutorJob;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobApplicationService
{
    use HelperTrait;

    public function getByTutorId( $request ,$tutorId )
    {
        $query = AppliedJob::where('tutor_id', $tutorId)
            ->with('tutorJobs', 'tutorJobs.user:id,name,email,username,profile_image,gender');

        // This week filter
        if ($request && $request->this_week == 1) {
            $query->whereBetween('applied_jobs.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        // This month filter
        if ($request && $request->this_month == 1) {
            $query->whereBetween('applied_jobs.created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        }

        return $query->latest()->get();
    }

    public function apply(array $data)
    {
        return AppliedJob::create($data);
    }

    public function getById($id)
    {
        return AppliedJob::with('tutorJobs')->findOrFail($id);
    }

    public function delete($id)
    {
        return AppliedJob::findOrFail($id)->delete();
    }

    public function hireTutorList(Request $request)
    {

        $query = TutorJob::query();
        $query->with(['medium', 'subjects', 'kid', 'institutes', 'grade', 'user:id,name,email,username,profile_image,gender', 'division', 'district', 'upazila', 'area']);
        $query->select([
            'tutor_jobs.*',
            'tutor_jobs.id as tutor_job_id',
            'aj.id as applied_job_id',
            'aj.status as applied_job_status',
            'aj.is_linked_up',
            'aj.linked_up_with_id',
            'aj.linked_up_start_at',
            'aj.linked_up_end_at'
        ]);
        $query->leftJoin('applied_jobs as aj', 'tutor_jobs.id', '=', 'aj.job_id');
        $query->where('aj.tutor_id', auth()->id());
        $query->where('aj.is_linked_up', 1);
        $query->where('status', 'accepted');

        // This week filter
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

        $query->orderBy('tutor_jobs.id', 'desc');

        return $query->get();
    }
}
