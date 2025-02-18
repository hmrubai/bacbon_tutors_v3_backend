<?php

namespace App\Services;

use App\Models\Medium;
use App\Http\Traits\HelperTrait;

class MediumService
{
    use HelperTrait;

    public function getAll()
    {
        return Medium::all();
    }

    public function create(array $data)
    {
        return Medium::create($data);
    }

    public function getById($id)
    {
        return Medium::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $medium = Medium::findOrFail($id);
        $medium->update($data);
        return $medium;
    }

    public function delete($id)
    {
        $medium = Medium::findOrFail($id);
        $medium->delete();
        return ['message' => 'Deleted successfully'];
    }
}
