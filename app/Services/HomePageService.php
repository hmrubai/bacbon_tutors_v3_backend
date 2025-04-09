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
        // Fetch active tutors and jobs count
        $data = [
            'active_tutors'   => User::where('user_type', "Teacher")->count(),
            'active_jobs'     => TutorJob::where('job_status', "Open")->count(),
            'hotline_no'      => "+88 09611 900 205",
            'profile_completion' => 72,
        ];

        // Get authenticated user ID once
        $userId = auth()->id() ?? 0;

        // Fetch bookmarked job IDs in a single query
        $bookmarkedJobs = \DB::table('tuition_bookmarks')
            ->where('user_id', $userId)
            ->pluck('tutor_job_id')
            ->toArray();
        $appliedJobs = \DB::table('applied_jobs')
            ->where('tutor_id', $userId)
            ->pluck('job_id')
            ->toArray();

        // Fetch available tutors with subject expertise
        $data['available_tutors'] = User::where('user_type', "Teacher")
            ->select('users.*')  // Add this to select all user fields
            ->selectRaw('(SELECT institute FROM tutor_education_histories WHERE tutor_education_histories.user_id = users.id ORDER BY passing_year DESC, sequence DESC LIMIT 1) as institute')
            ->with([
                'subjectExpertise:id,subject_id,medium_id,grade_id,user_id,location',
                'subjectExpertise.subject:id,name_en,name_bn'
            ])
            ->limit(10)
            ->get()
            ->map(fn($tutor) => $tutor->setAttribute('review', rand(10, 50) / 10));


        // Fetch available jobs with relationships and pre-check bookmarks
        $data['available_jobs'] = TutorJob::where('job_status', "Open")
            ->with(['medium', 'subjects', 'kid'])
            ->limit(10)
            ->get()
            ->map(function ($job) use ($bookmarkedJobs, $appliedJobs) {
                return $job->setAttribute('is_bookmark', in_array($job->id, $bookmarkedJobs) ? 1 : 0)
                    ->setAttribute('is_applied', in_array($job->id, $appliedJobs) ? 1 : 0)
                    ->setAttribute('job_type', 'HomeTution');
            });


        // Static key features and FAQ data
        $data['key_features'] = collect([
            ['icon' => 'icon1', 'title' => 'Free Profile Creation', 'description' => 'Create your profile for free and showcase your skills or services.'],
            ['icon' => 'icon2', 'title' => 'Easy Registration', 'description' => 'Sign up quickly with a simple and user-friendly registration process.'],
            ['icon' => 'icon3', 'title' => 'Expertise Tutor', 'description' => 'Find highly skilled tutors specializing in various subjects.'],
            ['icon' => 'icon4', 'title' => '100+ New Tuition Per Day', 'description' => 'Explore numerous tuition opportunities daily.']
        ]);

        $data['faq'] = collect([
            ['question' => 'How do I create a free profile?', 'answer' => 'To create a free profile, sign up using your email or social media account.'],
            ['question' => 'Is there any cost to register?', 'answer' => 'No, registration is completely free.'],
            ['question' => 'How can I find expert tutors?', 'answer' => 'Browse through the tutor directory and use search filters to find tutors.'],
            ['question' => 'How often are new tuition opportunities available?', 'answer' => 'New tuition opportunities are added daily.'],
            ['question' => 'Can I contact tutors directly?', 'answer' => 'Yes, you can send direct messages to tutors through the platform.'],
            ['question' => 'How do I upgrade to a premium plan?', 'answer' => 'Go to account settings, select an upgrade option, and complete the payment.']
        ]);

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


    public function studentDashboard(Request $request)
    {
        $data = [];
        $data['available_tutors'] = User::where('user_type', "Teacher")->with(['subjectExpertise:id,subject_id,medium_id,grade_id,user_id,location', 'subjectExpertise.subject:id,name_en,name_bn'])
            ->limit(5)->get();

        foreach ($data['available_tutors'] as $tutor) {
            $tutor['review'] = rand(10, 50) / 10;
        }


        $data['hotline_no'] = "+88 09611 900 205";
        $data['par_day_tutors'] = 99;

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
}
