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
                'course_id' => $data['course_id'],
                'date' => $data['date'],
            ],
            [
                'status' => $data['status'],
            ]
        );
    }
}
