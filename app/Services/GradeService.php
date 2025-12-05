<?php

namespace App\Services;

use App\Repositories\GradeRepository;

class GradeService extends BaseService
{
    public function __construct(GradeRepository $repository)
    {
        parent::__construct($repository);
    }
    public function updateOrCreateGrade(array $data)
    {
        return $this->repository->updateOrCreateGrade(
            [
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id'],
                'assessment_name' => $data['assessment_name'],
            ],
            [
                'score' => $data['score'],
                'max_score' => $data['max_score'],
            ]
        );
    }
}
