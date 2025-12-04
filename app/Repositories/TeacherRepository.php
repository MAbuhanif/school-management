<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Repositories\BaseRepository;

class TeacherRepository extends BaseRepository
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }
}
