<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->find($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'class_room_id' => 'sometimes|exists:class_rooms,id',
            'dob' => 'sometimes|date',
            'gender' => 'sometimes|string',
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
        ]);

        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
