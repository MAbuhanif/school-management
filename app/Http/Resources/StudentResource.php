<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'class_room_id' => $this->class_room_id,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'address' => $this->address,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->whenLoaded('user'),
            'class_room' => $this->whenLoaded('classRoom'),
            'can' => [
                'edit' => $request->user() ? $request->user()->can('update', $this->resource) : false,
                'delete' => $request->user() ? $request->user()->can('delete', $this->resource) : false,
            ],
        ];
    }
}
