<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRoomBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'start_time' => 'required|date|after:now',
            // 'end_time' => 'required|date|after:start_time',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'user_id' => 'required',
            'room_id' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'start_time.after' => 'Start time must be in the future.',
            'end_time.after' => 'End time must be after the start time.',
        ];
    }
}
