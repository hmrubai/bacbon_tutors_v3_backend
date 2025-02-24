<?php

namespace App\Services;

use App\Models\Reference;
use App\Http\Traits\HelperTrait;

class ReferenceService
{
    use HelperTrait;
    public function getAll()
    {
        return Reference::all();
    }
    
    // Get all references for a specific tutor (by user_id)
    public function getByTutorId($userId)
    {
        return Reference::where('user_id', $userId)->get();
    }

    public function create($data)
    {
        return Reference::create([
            'name'         => $data['name'],
            'designation'  => $data['designation'] ?? null,
            'organization' => $data['organization'],
            'phone'        => $data['phone'] ?? null,
            'email'        => $data['email'] ?? null,
            'user_id'      => $data['user_id'], // must be set from Auth
        ]);
    }

    public function getById($id)
    {
        return Reference::findOrFail($id);
    }

    public function update($id, $data)
    {
        $reference = Reference::findOrFail($id);
        $reference->update($data);
        return $reference;
    }

    public function delete($id)
    {
        $reference = Reference::findOrFail($id);
        $reference->delete();
        return ['message' => 'Deleted successfully'];
    }
}
