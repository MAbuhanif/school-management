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
    public function updateOrCreateGrade(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
