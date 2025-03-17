<?php

namespace App\Services;

use App\Models\SubjectExpertise;
use App\Models\ExpertiseClassList;
use App\Http\Traits\HelperTrait;

class SubjectExpertiseService
{
    use HelperTrait;

    public function getAll()
    {
        $subject_expertise = SubjectExpertise::select('subject_expertise.*', 
            'mediums.title_en as medium_title_en', 
            'mediums.title_bn as medium_title_bn', 
            'subjects.name_en as subject_name_en',
            'subjects.name_bn as subject_name_bn'
        )
        ->leftJoin('mediums', 'mediums.id', 'subject_expertise.medium_id')
        ->leftJoin('subjects', 'subjects.id', 'subject_expertise.subject_id')
        ->with(['medium', 'subject'])
        ->get();

        foreach ($subject_expertise as $item) {
            $item->class_list = ExpertiseClassList::select('expertise_class_lists.*', 'grade.name_en as grade_name_en',
                'grade.name_bn as grade_name_bn')
                ->where('subject_expertise_id', $item->id)
                ->leftJoin('grade', 'grade.id', 'expertise_class_lists.grade_id')
                ->get();
        }

        return $subject_expertise;
    }

    public function getByTutorId($userId)
    {
        $subject_expertise = SubjectExpertise::select('subject_expertise.*', 
            'mediums.title_en as medium_title_en', 
            'mediums.title_bn as medium_title_bn', 
            'subjects.name_en as subject_name_en',
            'subjects.name_bn as subject_name_bn'
        )
        ->leftJoin('mediums', 'mediums.id', 'subject_expertise.medium_id')
        ->leftJoin('subjects', 'subjects.id', 'subject_expertise.subject_id')
        ->where('subject_expertise.user_id', $userId)
        ->with(['medium', 'subject'])
        ->get();

        foreach ($subject_expertise as $item) {
            $item->class_list = ExpertiseClassList::select('expertise_class_lists.*', 'grade.name_en as grade_name_en',
                'grade.name_bn as grade_name_bn')
                ->where('subject_expertise_id', $item->id)
                ->leftJoin('grade', 'grade.id', 'expertise_class_lists.grade_id')
                ->get();
        }

        return $subject_expertise;

        return SubjectExpertise::with(['medium', 'class_list', 'subject'])
            ->where('user_id', $userId)
            ->get();
    }

    public function create($data)
    {
        $expertise = SubjectExpertise::create([
            'medium_id'  => $data['medium_id'],
            'grade_id'   => null,
            'subject_id' => $data['subject_id'],
            'user_id'    => $data['user_id'],
            'remarks'    => $data['remarks'] ?? null,
            'status'     => isset($data['status']) ? $data['status'] : true,
        ]);

        foreach ($data['grade_id'] as $item) {
            ExpertiseClassList::create([
                'subject_expertise_id' => $expertise->id,
                'grade_id' => $item,
                'user_id' => $data['user_id'],
                'status' => true
            ]);
        }
        return $expertise;
    }

    public function getById($id)
    {
        return SubjectExpertise::with(['medium', 'class_list', 'subject'])->findOrFail($id);
    }

    public function update($id, $data)
    {
        $expertise = SubjectExpertise::findOrFail($id);
        $expertise->update($data);
        return $expertise;
    }

    public function delete($id)
    {
        $expertise = SubjectExpertise::findOrFail($id);
        $expertise->delete();
        return ['message' => 'Deleted successfully'];
    }
}
