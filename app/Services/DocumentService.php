<?php

namespace App\Services;

use App\Models\Document;
use App\Http\Traits\HelperTrait;

class DocumentService
{
    use HelperTrait;

    public function getAll()
    {
        return Document::all();
    }

    public function getByUserId($userId)
    {
        return Document::where('user_id', $userId)->get();
    }

    public function create(array $data)
    {
        return Document::create($data);
    }

    public function getById($id)
    {
        return Document::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $document = Document::findOrFail($id);
        $document->update($data);
        return $document;
    }

    public function delete($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return ['message' => 'Deleted successfully'];
    }
}
