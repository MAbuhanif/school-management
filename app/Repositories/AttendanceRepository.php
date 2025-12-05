<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Repositories\BaseRepository;

class AttendanceRepository extends BaseRepository
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }
    public function updateOrCreateAttendance(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
