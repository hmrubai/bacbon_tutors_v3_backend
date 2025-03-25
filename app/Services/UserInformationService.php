<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;

class UserInformationService
{
    use HelperTrait;

    public function showUser(int $userId): User
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
            'tutorSchedules'
        )->findOrFail($userId);
    
        // Add the review attribute without modifying the model structure
        $user->review=rand(10, 50) / 10;
    
        return $user;
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
