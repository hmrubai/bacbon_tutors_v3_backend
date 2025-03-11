<?php

namespace App\Services;
use App\Models\TutorJob;
use Illuminate\Support\Str;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;

class TutorJobService
{
    use HelperTrait;

    public function getByUserId($userId)
    {
        return TutorJob::with(['medium', 'subject', 'kid'])
                  ->where('user_id', $userId)
                  ->get();
    }

    public function getById($id)
    {
        return TutorJob::with(['medium', 'subject', 'kid'])->findOrFail($id);
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

    public function allJobs()
    {
        return TutorJob::with(['medium', 'subject', 'kid'])
                  ->where('job_status', "Open")
                  ->get();
    }

    public function jobDetailsByID($id)
    {
        return TutorJob::with(['medium', 'subject', 'kid'])->findOrFail($id);
    }
}
