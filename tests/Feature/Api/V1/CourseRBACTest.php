<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRBACTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    private function createTeacherUser()
    {
        $user = User::factory()->create();
        $user->assignRole('teacher');
        return $user;
    }

    private function createStudentUser()
    {
        $user = User::factory()->create();
        $user->assignRole('student');
        return $user;
    }

    private function createParentUser()
    {
        $user = User::factory()->create();
        $user->assignRole('parent');
        return $user;
    }

    private function courseData()
    {
        $classRoom = ClassRoom::factory()->create();
        $teacher = Teacher::factory()->create();
        $subject = Subject::factory()->create();
        return [
            'name' => 'Physics 101',
            'class_room_id' => $classRoom->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
        ];
    }

    public function test_teacher_can_create_course()
    {
        $teacher = $this->createTeacherUser();
        $data = $this->courseData();
        $response = $this->actingAs($teacher)->postJson('/api/v1/courses', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Physics 101']);
    }

    public function test_student_cannot_create_course()
    {
        $student = $this->createStudentUser();
        $data = $this->courseData();
        $response = $this->actingAs($student)->postJson('/api/v1/courses', $data);
        $response->assertStatus(403);
    }

    public function test_parent_cannot_create_course()
    {
        $parent = $this->createParentUser();
        $data = $this->courseData();
        $response = $this->actingAs($parent)->postJson('/api/v1/courses', $data);
        $response->assertStatus(403);
    }
}
