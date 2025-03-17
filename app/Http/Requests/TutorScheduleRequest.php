<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow only authenticated users.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday|unique:tutor_schedules,day_of_week,NULL,id,user_id,' . auth()->id(),
            'morning'     => 'nullable|boolean',
            'afternoon'   => 'nullable|boolean',
            'evening'     => 'nullable|boolean',
            // user_id will be auto-populated from Auth in the service/controller.
        ];
    }
}
