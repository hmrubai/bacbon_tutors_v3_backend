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
        $data['active_jobs'] = TutorJob::where('job_status', "Open")->get()->count();

        $data['available_tutors'] = User::where('user_type', "Teacher")->limit(10)->get();
        
        foreach ($data['available_tutors'] as $tutor) {
            $tutor['review'] = rand(1, 5);
        }

        $joblist = TutorJob::where('job_status', "Open")->with(['medium', 'subjects', 'kid'])->limit(10)->get();
        foreach ($joblist as $job) {
            $job['is_bookmark'] = false;
            $job['job_type'] = "HomeTution";
        }
        $data['available_jobs'] = $joblist;

        $data['hotline_no'] = "+88 09611 900 205";
        $data['profile_completion'] = 72;

        $data['key_features'] = [
            [
                'icon' => 'icon1',
                'title' => 'Free Profile Creation',
                'description' => 'Create your profile for free and showcase your skills or services. Customize your profile with essential details, upload your portfolio, and connect with potential clients or employers effortlessly.'
            ],
            [
                'icon' => 'icon2',
                'title' => 'Easy Registration',
                'description' => 'Sign up quickly with a simple and user-friendly registration process. Provide basic details and get started in just a few clicks, without any complicated steps.'
            ],
            [
                'icon' => 'icon3',
                'title' => 'Expertise Tutor',
                'description' => 'Find highly skilled tutors specializing in various subjects. Our platform connects students with experienced educators to ensure effective learning and academic success.'
            ],
            [
                'icon' => 'icon4',
                'title' => '100+ New Tuition Per Day',
                'description' => 'Explore numerous tuition opportunities daily. Whether you are a tutor looking for students or a learner seeking guidance, our platform updates with fresh tuition listings regularly.'
            ],
        ];

        $data['faq'] = [
            [
                'question' => 'How do I create a free profile?',
                'answer' => 'To create a free profile, simply sign up using your email or social media account, fill in your basic information, and customize your profile as needed. No payment is required.'
            ],
            [
                'question' => 'Is there any cost to register?',
                'answer' => 'No, registration is completely free. You can create a profile and access basic features at no cost. However, premium features may require a subscription.'
            ],
            [
                'question' => 'How can I find expert tutors?',
                'answer' => 'You can browse through the tutor directory, use search filters to find tutors based on expertise, and check their profiles for ratings, reviews, and availability.'
            ],
            [
                'question' => 'How often are new tuition opportunities available?',
                'answer' => 'New tuition opportunities are added daily. You can check the platform regularly or enable notifications to stay updated on the latest listings.'
            ],
            [
                'question' => 'Can I contact tutors directly?',
                'answer' => 'Yes, you can send direct messages to tutors through the platform. Some tutors may also provide their contact details for direct communication.'
            ],
            [
                'question' => 'How do I upgrade to a premium plan?',
                'answer' => 'To upgrade to a premium plan, go to your account settings, select the upgrade option, choose a plan that fits your needs, and complete the payment process.'
            ],
        ];


        return $data;
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
