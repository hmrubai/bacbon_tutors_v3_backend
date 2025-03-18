<?php

namespace App\Services;

use App\Models\TutorSchedule;
use App\Http\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorScheduleService
{
    use HelperTrait;

    public function getByUserId($userId)
    {
        return TutorSchedule::where('user_id', $userId)->get();
    }

    public function getById($id)
    {
        return TutorSchedule::findOrFail($id);
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return TutorSchedule::create($data);
    }

    public function createOrUpdate(array $data)
    {
        $data['user_id'] = Auth::id();

        $is_exist = TutorSchedule::where('user_id', Auth::id())
            ->where('day_of_week', $data['day_of_week'])
            ->first();

        if($is_exist){
            $is_exist->update($data);
        }else{
            TutorSchedule::create($data);
        }

        return TutorSchedule::where('user_id', Auth::id())->get();
    }

    public function update($id, array $data)
    {
        $record = TutorSchedule::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = TutorSchedule::findOrFail($id);
        $record->delete();
        return ['message' => 'Deleted successfully'];
    }
}
