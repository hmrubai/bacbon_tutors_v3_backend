<?php

namespace App\Services;

use App\Models\AppliedJob;
use App\Http\Traits\HelperTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobApplicationService
{
    use HelperTrait;

    public function getByTutorId($tutorId)
    {
        return AppliedJob::where('tutor_id', $tutorId)->with('tutorJobs')->latest()->get();
    }

    public function apply(array $data)
    {
        return AppliedJob::create($data);
    }

    public function getById($id)
    {
        return AppliedJob::findOrFail($id);
    }

    public function delete($id)
    {
        return AppliedJob::findOrFail($id)->delete();
    }
}
