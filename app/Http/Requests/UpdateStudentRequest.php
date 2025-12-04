<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'class_room_id' => 'sometimes|exists:class_rooms,id',
            'dob' => 'sometimes|date',
            'gender' => 'sometimes|string',
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
        ];
    }
}
