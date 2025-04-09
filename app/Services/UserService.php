<?php

namespace App\Services;

use App\Models\User;
use App\Http\Traits\HelperTrait;
use App\Models\AppliedJob;
use App\Models\TutorFavorite;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class UserService
{
    use HelperTrait;

    public function userList(Request $request, $userType): Collection|LengthAwarePaginator|array
    {
        $query = User::query();
        $query->where('user_type', $userType);

        // Select specific columns
        $query->select(['*']);

        // Sorting
        $this->applySorting($query, $request);

        // Searching
        $searchKeys = [
            'name',
            'email',
            'username'
        ]; // Define the fields you want to search by
        $this->applySearch($query, $request->input('search'), $searchKeys);

        // Pagination
        return $this->paginateOrGet($query, $request);
    }
    public function index(Request $request): Collection|LengthAwarePaginator|array
    {
        $query = User::query();
        $query->whereNotIn('id', [auth()->user()->id, 1, 2]);

        // Select specific columns
        $query->select(['*']);

        // Sorting
        $this->applySorting($query, $request);

        // Searching
        $searchKeys = ['name']; // Define the fields you want to search by
        $this->applySearch($query, $request->input('search'), $searchKeys);

        // Pagination
        return $this->paginateOrGet($query, $request);
    }

    public function store(Request $request)
    {
        $data = $this->prepareUserData($request);

        return User::create($data);
    }

    private function prepareUserData(Request $request, bool $isNew = true): array
    {
        // Get the fillable fields from the model
        $fillable = (new User())->getFillable();

        // Extract relevant fields from the request dynamically
        $data = $request->only($fillable);

        // Handle file uploads
        $data['image'] = $this->ftpFileUpload($request, 'image', 'image');
        $data['password'] = bcrypt($request->input('password'));
        //$data['cover_picture'] = $this->ftpFileUpload($request, 'cover_picture', 'user');

        // Add created_by and created_at fields for new records
        if ($isNew) {
            $data['created_by'] = auth()->user()->id;
            $data['created_at'] = now();
        }

        return $data;
    }

    public function show(int $id): User
    {
        return User::whereNotIn('id', [auth()->user()->id, 1, 2])
            ->findOrFail($id);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $updateData = $this->prepareUserData($request, false);
        $user->update($updateData);

        return $user;
    }

    public function destroy(int $id): bool
    {
        $user = User::findOrFail($id);
        $user->name .= '_' . Str::random(8);
        $user->email .= '_' . Str::random(8);
        $user->username .= '_' . Str::random(8);
        $user->primary_number .= '_' . Str::random(8);
        $user->alternate_number .= '_' . Str::random(8);
        $user->deleted_at = now();

        return $user->save();
    }

    public function tutorDetails($request, $id)
    {
        $authId = auth()->id();
        $user = User::where('id', $id)
            ->with([
                'subjectExpertise',
                'workExperiences',
                'educationHistory',
                'references',
                'address',
                'documents',
                'tutionAreas',
                'tutorSchedules',
            ])

            ->first();

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

        //favorite tutor
        $user->is_favorite = TutorFavorite::where('user_id', $authId)
            ->where('tutor_id', $user->id)
            ->exists() ?? false;

        return $user;
    }
}
