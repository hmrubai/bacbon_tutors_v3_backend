<?php

namespace App\Services;

use App\Models\Job;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;

class JobService
{
    use HelperTrait;

    /**
     * Retrieve all jobs for the currently authenticated user,
     * eager loading related medium, subject, and kid information.
     */
    public function getByUserId($userId)
    {
        return Job::with(['medium', 'subject', 'kid'])
                  ->where('user_id', $userId)
                  ->get();
    }

    /**
     * Retrieve a specific job by its ID with related data.
     */
    public function getById($id)
    {
        return Job::with(['medium', 'subject', 'kid'])->findOrFail($id);
    }

    /**
     * Create a new job record.
     */
    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return Job::create($data);
    }

    /**
     * Update an existing job record.
     */
    public function update($id, array $data)
    {
        $job = Job::findOrFail($id);
        unset($data['user_id']);
        $job->update($data);
        return $job;
    }

    /**
     * Delete a job record.
     */
    public function delete($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return ['message' => 'Deleted successfully'];
    }
}
