<?php

namespace App\Services;

use App\Repositories\TeacherRepository;

class TeacherService extends BaseService
{
    public function __construct(TeacherRepository $repository)
    {
        parent::__construct($repository);
    }
}
