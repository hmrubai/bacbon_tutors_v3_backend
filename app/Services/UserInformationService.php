<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;

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

        // Calculate profile completion percentage
        $completionScore = 0;

        if ($user->id) {
            $completionScore += 10;
        }

        if ($user->documents && $user->documents->count() > 0) {
            $completionScore += 10;
        }

        if ($user->educationHistory && $user->educationHistory->count() > 0) {
            $completionScore += 15;
        }
        if ($user->subjectExpertise && $user->subjectExpertise->count() > 0) {
            $completionScore += 25;
        }
        if ($user->tutionAreas && $user->tutionAreas->count() > 0) {
            $completionScore += 10;
        }
        if ($user->tutorSchedules && $user->tutorSchedules->count() > 0) {
            $completionScore += 15;
        }
        $user->profile_progress = $completionScore;
        return $user;
    }



    public function showStudent($user)
    {

        return User::with([
            'educationHistory',
            'presentDivision',
            'presentDistrict',
            'presentArea',
            'permanentDivision',
            'permanentDistrict',
            'presentUpazila',
            'permanentUpazila',
            'permanentArea'
        ])->findOrFail($user->id);
    }
    public function showGuardian($user)
    {

        return User::with(['kids', 'kids.grade', 'kids.medium', 'documents'])->findOrFail($user->id);
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
