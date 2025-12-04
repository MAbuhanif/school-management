<?php

namespace App\Repositories;

use App\Models\Grade;
use App\Repositories\BaseRepository;

class GradeRepository extends BaseRepository
{
    public function __construct(Grade $model)
    {
        parent::__construct($model);
    }
}
