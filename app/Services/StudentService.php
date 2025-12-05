<?php

namespace App\Services;

use App\Repositories\StudentRepository;

class StudentService extends BaseService
{
    public function __construct(StudentRepository $repository)
    {
        parent::__construct($repository);
    }
    public function getPaginatedList(int $perPage = 10, array $filters = [], string $sortBy = 'created_at', string $sortDir = 'desc')
    {
        return $this->repository->getPaginatedList($perPage, $filters, $sortBy, $sortDir);
    }

    public function bulkDelete(array $ids)
    {
         return $this->repository->bulkDelete($ids);
    }
}
