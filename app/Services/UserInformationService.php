<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use App\Http\Traits\HelperTrait;
use App\Models\AppliedJob;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserInformationService
{
    use HelperTrait;

    public function showUser($user): User
    {
        $user = User::with(
            'subjectExpertise.medium',
            'subjectExpertise.grade',
            'subjectExpertise.subject',
            'workExperiences',
            'educationHistory',
            'references',
            'address',
            'documents',
            'tutionAreas',
            'tutorSchedules',
            'presentDivision',
            'presentDistrict',
            'presentArea',
            'permanentDivision',
            'permanentDistrict',
            'presentUpazila',
            'permanentUpazila',
            'permanentArea'
        )->findOrFail($user->id);

        // Add the review attribute without modifying the model structure
        $user->review = rand(10, 50) / 10;
        $user->total_tuition = (int) AppliedJob::where('tutor_id', $user->id)
            ->where('is_linked_up', 1)
            ->count();
        $user->experience = (int)$user->workExperiences->sum(function ($experience) {
            $startDate = Carbon::parse($experience->start_date);
            $endDate = $experience->currently_working ? Carbon::now() : Carbon::parse($experience->end_date);
            $years = $startDate->diffInMonths($endDate) / 12;
            return round($years, 1);
        });
        $user->joined_here = (string) Carbon::parse($user->created_at)->diffForHumans();

        return $user;
    }



    public function showStudent($user)
    {

        return User::with('educationHistory')->findOrFail($user->id);
    }
    public function show(int $userId): UserInformation
    {
        return UserInformation::findOrFail($userId);
    }

    public function update(Request $request, int $userId): UserInformation
    {
        $userInfo = UserInformation::findOrFail($userId);

        // Get all input except the file input 'profile_image'
        $data = $request->except('profile_image');

        // If a new profile image is uploaded, use the helper to upload and get the path
        if ($request->hasFile('profile_image')) {
            $filePath = $this->fileUpload($request, 'profile_image', 'users');
            $data['profile_image'] = $filePath;
        }

        $userInfo->update($data);
        return $userInfo;
    }
}
