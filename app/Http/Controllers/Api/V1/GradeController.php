<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\GradeService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $service;

    public function __construct(GradeService $service)
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
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'assessment_type' => 'required|string',
            'score' => 'required|integer|min:0|max:100',
            'weight' => 'required|integer|min:0|max:100',
        ]);

        return response()->json($this->service->create($data), 201);
    }
}
