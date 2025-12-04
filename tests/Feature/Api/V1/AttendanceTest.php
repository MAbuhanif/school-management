<?php

namespace Tests\Feature\Api\V1;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_mark_attendance()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $data = [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'date' => now()->toDateString(),
            'status' => 'present',
        ];

        $response = $this->postJson('/api/v1/attendances', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => 'present']);
    }

    public function test_can_bulk_mark_attendance()
    {
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        $course = Course::factory()->create();

        $data = [
            'attendances' => [
                [
                    'student_id' => $student1->id,
                    'course_id' => $course->id,
                    'date' => now()->toDateString(),
                    'status' => 'present',
                ],
                [
                    'student_id' => $student2->id,
                    'course_id' => $course->id,
                    'date' => now()->toDateString(),
                    'status' => 'absent',
                ],
            ]
        ];

        $response = $this->postJson('/api/v1/attendances/bulk', $data);

        $response->assertStatus(201)
            ->assertJsonCount(2);
    }
}
