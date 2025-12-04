<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\BaseRepository;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }
}
