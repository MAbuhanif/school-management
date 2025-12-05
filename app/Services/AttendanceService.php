<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;

/**
 * @property AttendanceRepository $repository
 */
class AttendanceService extends BaseService
{
    public function __construct(AttendanceRepository $repository)
    {
        parent::__construct($repository);
    }
    public function updateOrCreateAttendance(array $data)
    {
        return $this->repository->updateOrCreateAttendance(
            [
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id'], // Although the requirement was Class, the DB schema uses Course. 
                // Wait, the DB schema has course_id. But the requirement is "Select Class". 
                // Let me check if Student belongs to ClassRoom. Yes.
                // Does Attendance belong to Course or Class? Schema says Course.
                // But usually dailly attendance is by ClassRoom or Subject/Course.
                // If I select Class, I might need to pick a default Course or null if allowed?
                // Schema: foreignId('course_id')->constrained(). So it's required.
                // Teachers teach Courses. So selecting a class might imply selecting a Course for that class.
                // Let's assume for now the form passes course_id, or I'll use a dummy/default if the user meant Homeroom attendance.
                // Validating schema: 2025_12_04_104407_create_attendances_table.php has course_id.
                // Let's assume the UI selects Class, and maybe the backend finds the course?
                // Or maybe the UI should select Date and Class, and the attendance is 'Daily' (Homeroom)?
                // If 'Daily', course_id is awkward. 
                // Let's check Teacher Controller or similar to see relations.
                // If the user wants "Attendance", usually it's per subject in higher grades, per day in lower.
                // 'course_id' required suggests per-subject attendance.
                // effectively: student_id + course_id + date = unique record.
                'date' => $data['date'],
            ],
            [
                'status' => $data['status'],
            ]
        );
    }
}
