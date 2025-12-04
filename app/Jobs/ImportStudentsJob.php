<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportStudentsJob implements ShouldQueue
{
    use Queueable;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data as $studentData) {
            // Simulate import logic, e.g., creating students
            // Student::create($studentData);
            \Illuminate\Support\Facades\Log::info('Importing student: ' . ($studentData['name'] ?? 'Unknown'));
        }
    }
}
