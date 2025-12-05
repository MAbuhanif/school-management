<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\BaseRepository;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function getPaginatedList(int $perPage = 10, array $filters = [], string $sortBy = 'created_at', string $sortDir = 'desc')
    {
        $query = $this->model->with(['user', 'classRoom']);

        // Check if we need to search by user attributes (name, email)
        if (!empty($filters['search']) || $sortBy === 'name' || $sortBy === 'email') {
            $query->join('users', 'students.user_id', '=', 'users.id')
                  ->select('students.*'); // Ensure we fetch student fields
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($filters['class_room_id'])) {
            $query->where('class_room_id', $filters['class_room_id']);
        }

        if (!empty($filters['status'])) {
            // Assuming there is a status column, otherwise ignore or implement logic
             $query->where('status', $filters['status']);
        }

        // Sorting
        if (in_array($sortBy, ['name', 'email'])) {
             $query->orderBy('users.' . $sortBy, $sortDir);
        } else {
             $query->orderBy('students.' . $sortBy, $sortDir);
        }

        return $query->paginate($perPage);
    }

    public function bulkDelete(array $ids)
    {
        return $this->model->whereIn('id', $ids)->delete();
    }
}
