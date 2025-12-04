<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.course_id' => 'required|exists:courses,id',
            'attendances.*.date' => 'required|date',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
        ]);

        $created = [];
        foreach ($data['attendances'] as $attendanceData) {
            $created[] = $this->service->create($attendanceData);
        }

        return response()->json($created, 201);
    }
}
