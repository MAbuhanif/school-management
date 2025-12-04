<?php

namespace Tests\Feature;

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
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->post('/attendance');

        $response->assertStatus(200);
    }

    public function test_student_cannot_mark_attendance()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->post('/attendance');

        $response->assertStatus(403);
    }

    public function test_parent_cannot_mark_attendance()
    {
        $parent = User::factory()->create();
        $parent->assignRole('parent');

        $response = $this->actingAs($parent)->post('/attendance');

        $response->assertStatus(403);
    }
}
