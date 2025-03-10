<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInformation;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;

class UserInformationService
{
    use HelperTrait;

<<<<<<< HEAD
    /**
     * Retrieve the current user's information.
     */
    public function show(int $userId)
=======
    public function showUser(int $userId): User
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    {
        return User::with(
            'subjectExpertise.medium',
            'subjectExpertise.grade',
            'subjectExpertise.subject',
            'workExperiences',
            'references',
            'address',
            'documents',
            'tutionAreas',
            'tutorSchedules',
        )->findOrFail($userId);
    }

<<<<<<< HEAD
    public function showUser(int $userId): UserInformation
    {
        return UserInformation::findOrFail($userId);
    }
    /**
     * Update the current user's information.
     */
=======
    public function show(int $userId): UserInformation
    {
        return UserInformation::findOrFail($userId);
    }

>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
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
