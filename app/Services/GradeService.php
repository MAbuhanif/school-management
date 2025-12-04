<?php

namespace App\Services;

use App\Repositories\GradeRepository;

class GradeService extends BaseService
{
    public function __construct(GradeRepository $repository)
    {
        parent::__construct($repository);
    }
}
