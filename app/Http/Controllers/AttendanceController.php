<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    protected $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $classRoomId = $request->input('class_room_id');
        $date = $request->input('date', now()->format('Y-m-d'));
        
        // For simplicity, let's assume one Course for now or allow selecting it.
        // If the user wants "Attendance", and the schema demands Course, we need a Course.
        // Let's add a Course selector to the UI as well.
        $courseId = $request->input('course_id');

        $students = [];
        if ($classRoomId) {
             $students = Student::where('class_room_id', $classRoomId)
                ->with(['user', 'attendances' => function($query) use ($date, $courseId) {
                    $query->where('date', $date);
                    if ($courseId) {
                        $query->where('course_id', $courseId);
                    }
                }])
                ->get()
                ->map(function ($student) {
                    // Flatten attendance status for easier frontend access
                    $attendance = $student->attendances->first();
                    $student->attendance_status = $attendance ? $attendance->status : null;
                    return $student;
                });
        }

        return Inertia::render('Attendance/Index', [
            'classRooms' => ClassRoom::all(),
            'courses' => Course::all(), // Passing courses for selection
            'students' => $students,
            'filters' => [
                'class_room_id' => $classRoomId,
                'course_id' => $courseId,
                'date' => $date,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
        ]);

        $this->service->updateOrCreateAttendance($validated);

        return redirect()->back();
    }
}
