<?php

namespace App\Services;

use App\Models\HomePage;
use App\Http\Traits\HelperTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\TutorJob;

class HomePageService
{
    use HelperTrait;

    public function homePageDetailsByUser(Request $request)
    {
        $data = [];

        $data['active_tutors'] = User::where('user_type', "Teacher")->get()->count();
        $data['available_tutors'] = User::where('user_type', "Teacher")->limit(10)->get();
        $joblist = TutorJob::where('job_status', "Open")->limit(10)->get();
        foreach ($joblist as $job) {
            $job['is_bookmark'] = false;
            $job['job_type'] = "HomeTution";
        }

        $data['available_jobs'] = $joblist;
        $data['key_features'] = [
            [
                'icon' => 'icon1',
                'title' => 'Free Profile Creation',
                'description' => 'Free Profile Creation allows users to register and set up a personal or business profile without any cost. It typically includes providing basic information (like name, bio, and contact details), customizing the profile with additional relevant fields, and setting privacy preferences. Users can access basic features, such as viewing others\' profiles or engaging with content. While the profile creation itself is free, some platforms may offer premium features for an upgraded experience. This service helps build communities and networks on various platforms.',
            ],
            [
                'icon' => 'icon1',
                'title' => 'Easy Registration',
                'description' => 'Free Profile Creation allows users to register and set up a personal or business profile without any cost. It typically includes providing basic information (like name, bio, and contact details), customizing the profile with additional relevant fields, and setting privacy preferences. Users can access basic features, such as viewing others\' profiles or engaging with content. While the profile creation itself is free, some platforms may offer premium features for an upgraded experience. This service helps build communities and networks on various platforms.',
            ],
            [
                'icon' => 'icon1',
                'title' => 'Expertise Tutor',
                'description' => 'Free Profile Creation allows users to register and set up a personal or business profile without any cost. It typically includes providing basic information (like name, bio, and contact details), customizing the profile with additional relevant fields, and setting privacy preferences. Users can access basic features, such as viewing others\' profiles or engaging with content. While the profile creation itself is free, some platforms may offer premium features for an upgraded experience. This service helps build communities and networks on various platforms.',
            ],
            [
                'icon' => 'icon1',
                'title' => '100+  New tuition per day',
                'description' => 'Free Profile Creation allows users to register and set up a personal or business profile without any cost. It typically includes providing basic information (like name, bio, and contact details), customizing the profile with additional relevant fields, and setting privacy preferences. Users can access basic features, such as viewing others\' profiles or engaging with content. While the profile creation itself is free, some platforms may offer premium features for an upgraded experience. This service helps build communities and networks on various platforms.',
            ],
        ];

    }

    // public function index(Request $request): Collection|LengthAwarePaginator|array
    // {
    //     $query = HomePage::query();

    //     // Select specific columns
    //     $query->select(['*']);

    //     // Sorting
    //     $this->applySorting($query, $request);

    //     // Searching
    //     $searchKeys = ['name']; // Define the fields you want to search by
    //     $this->applySearch($query, $request->input('search'), $searchKeys);

    //     // Pagination
    //     return $this->paginateOrGet($query, $request);
    // }

    // public function store(Request $request)
    // {
    //     $data = $this->prepareHomePageData($request);

    //     return HomePage::create($data);
    // }

    // private function prepareHomePageData(Request $request, bool $isNew = true): array
    // {
    //     // Get the fillable fields from the model
    //     $fillable = (new HomePage())->getFillable();

    //     // Extract relevant fields from the request dynamically
    //     $data = $request->only($fillable);

    //     // Handle file uploads
    //     //$data['thumbnail'] = $this->ftpFileUpload($request, 'thumbnail', 'homePage');
    //     //$data['cover_picture'] = $this->ftpFileUpload($request, 'cover_picture', 'homePage');

    //     // Add created_by and created_at fields for new records
    //     if ($isNew) {
    //         $data['created_by'] = auth()->user()->id;
    //         $data['created_at'] = now();
    //     }

    //     return $data;
    // }

    // public function show(int $id): HomePage
    // {
    //     return HomePage::findOrFail($id);
    // }

    // public function update(Request $request, int $id)
    // {
    //     $homePage = HomePage::findOrFail($id);
    //     $updateData = $this->prepareHomePageData($request, false);
    //     $homePage->update($updateData);

    //     return $homePage;
    // }

    // public function destroy(int $id): bool
    // {
    //     $homePage = HomePage::findOrFail($id);
    //     $homePage->name .= '_' . Str::random(8);
    //     $homePage->deleted_at = now();

    //     return $homePage->save();
    // }
}
