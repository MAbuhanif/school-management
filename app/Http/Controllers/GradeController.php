<?php

namespace App\Http\Controllers;

use App\Services\GradeService;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class GradeController extends Controller
{
    protected $service;

    public function __construct(GradeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $classRoomId = $request->input('class_room_id');
        $courseId = $request->input('course_id');
        $assessmentName = $request->input('assessment_name');
        
        $students = [];
        if ($classRoomId && $courseId) {
             $students = Student::where('class_room_id', $classRoomId)
                ->with(['user', 'grades' => function($query) use ($courseId, $assessmentName) {
                    $query->where('course_id', $courseId);
                    if ($assessmentName) {
                         $query->where('assessment_name', $assessmentName);
                    }
                }])
                ->get()
                ->map(function ($student) {
                    // If assessment name is provided, grab that specific grade
                    $student->current_grade = $student->grades->first() ? $student->grades->first()->score : '';
                    return $student;
                });
        }

        return Inertia::render('Grades/Index', [
            'classRooms' => ClassRoom::all(),
            'courses' => Course::all(),
            'students' => $students,
            'filters' => [
                'class_room_id' => $classRoomId,
                'course_id' => $courseId,
                'assessment_name' => $assessmentName,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'assessment_name' => 'required|string',
            'max_score' => 'required|numeric',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'required|numeric|min:0',
        ]);

        foreach ($data['grades'] as $gradeData) {
            $this->service->updateOrCreateGrade([
                'student_id' => $gradeData['student_id'],
                'course_id' => $data['course_id'],
                'assessment_name' => $data['assessment_name'],
                'score' => $gradeData['score'],
                'max_score' => $data['max_score'],
            ]);
        }

        return redirect()->back()->with('success', 'Grades saved successfully.');
    }

    public function reportCard(string $id)
    {
        $student = Student::with(['user', 'classRoom', 'attendances', 'grades.course'])->findOrFail($id);
        
        // Calculate attendance summary
        $totalDays = $student->attendances->count();
        $presentDays = $student->attendances->where('status', 'present')->count();
        $attendancePercentage = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;

        // Group grades by course
        $gradesByCourse = $student->grades->groupBy('course.title');

        $pdf = Pdf::loadView('reports.report_card', compact('student', 'attendancePercentage', 'gradesByCourse'));
        return $pdf->download('report_card_' . $student->user->name . '.pdf');
    }
}
