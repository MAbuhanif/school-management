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
        $studentId = $this->route('student');
        if (is_string($studentId) || is_numeric($studentId)) {
            $student = \App\Models\Student::findOrFail($studentId);
        } else {
            $student = $studentId;
        }
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'class_room_id' => 'required|exists:class_rooms,id',
            'profile_picture' => 'nullable|image|max:2048',
        ];
    }
}
