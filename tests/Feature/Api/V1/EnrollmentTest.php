<?php

namespace Tests\Feature\Api\V1;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_enroll_student()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $data = [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/v1/enrollments', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['student_id' => $student->id]);
    }

    public function test_can_unenroll_student()
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->deleteJson("/api/v1/enrollments/{$enrollment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }
}
