<?php

namespace App\Services;

use App\Models\Institute;
use App\Http\Traits\HelperTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstituteService
{
    use HelperTrait;

    public function index(Request $request): Collection|LengthAwarePaginator|array
    {
        $query = Institute::query();

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
        $data = $this->prepareInstituteData($request);

        return Institute::create($data);
    }

    private function prepareInstituteData(Request $request, bool $isNew = true): array
    {
        // Get the fillable fields from the model
        $fillable = (new Institute())->getFillable();

        // Extract relevant fields from the request dynamically
        $data = $request->only($fillable);

        // Handle file uploads
        //$data['thumbnail'] = $this->ftpFileUpload($request, 'thumbnail', 'institute');
        //$data['cover_picture'] = $this->ftpFileUpload($request, 'cover_picture', 'institute');

        // Add created_by and created_at fields for new records
        if ($isNew) {
            $data['created_by'] = auth()->user()->id;
            $data['created_at'] = now();
        }

        return $data;
    }

    public function show(int $id): Institute
    {
        return Institute::findOrFail($id);
    }

    public function update(Request $request, int $id)
    {
        $institute = Institute::findOrFail($id);
        $updateData = $this->prepareInstituteData($request, false);
        $institute->update($updateData);

        return $institute;
    }

    public function destroy(int $id): bool
    {
        $institute = Institute::findOrFail($id);
        $institute->title .= '_' . Str::random(8);
        $institute->deleted_at = now();

        return $institute->save();
    }

    public function institutionList()
    {
       return Institute::get();
    }
}
