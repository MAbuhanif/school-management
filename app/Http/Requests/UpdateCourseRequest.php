<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'class_room_id' => 'sometimes|required|exists:class_rooms,id',
            'teacher_id' => 'sometimes|required|exists:teachers,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('description')) {
            $this->merge([
                'description' => strip_tags($this->description, '<b><i><u><ul><ol><li><p><br>'),
            ]);
        }
    }
}
