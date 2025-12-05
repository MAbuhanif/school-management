<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_teacher_can_mark_attendance()
    {
        $user = User::factory()->create();
        $user->assignRole('teacher');

        // Create dependencies
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'present',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'status' => 'present',
        ]);
    }

    public function test_student_cannot_mark_attendance()
    {
        $user = User::factory()->create();
        $user->assignRole('student');

        // Create dependencies
        $course = Course::factory()->create();
        $student = Student::factory()->create();

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'present',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('attendances', [
            'student_id' => $student->id,
        ]);
    }
}
