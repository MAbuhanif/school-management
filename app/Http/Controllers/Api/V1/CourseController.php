<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(Request $request)
    {
        if (! $request->user()->hasRole('teacher')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validate([
            'name' => 'required|string',
            'class_room_id' => 'required|exists:class_rooms,id',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function update(Request $request, $id)
    {
        if (! $request->user()->hasRole('teacher')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validate([
            'name' => 'sometimes|string',
            'class_room_id' => 'sometimes|exists:class_rooms,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'subject_id' => 'sometimes|exists:subjects,id',
        ]);

        return response()->json($this->service->update($id, $data));
    }

    public function destroy(Request $request, $id)
    {
        if (! $request->user()->hasRole('teacher')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
