<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;

class EnrollmentService extends BaseService
{
    public function __construct(EnrollmentRepository $repository)
    {
        parent::__construct($repository);
    }
}
