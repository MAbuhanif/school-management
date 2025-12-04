<?php

namespace App\Services;

use App\Repositories\CourseRepository;

class CourseService extends BaseService
{
    public function __construct(CourseRepository $repository)
    {
        parent::__construct($repository);
    }
}
