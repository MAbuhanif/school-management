<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return StudentResource::collection($this->service->all());
    }

    public function store(StoreStudentRequest $request)
    {
        $student = $this->service->create($request->validated());
        return (new StudentResource($student))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        return new StudentResource($this->service->find($id));
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = $this->service->update($id, $request->validated());
        return new StudentResource($student);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
