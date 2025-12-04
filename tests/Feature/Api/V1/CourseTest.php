<?php

namespace Tests\Feature\Api\V1;

use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function teacherUser()
    {
        $user = User::factory()->create();
        $user->assignRole('teacher');
        return $user;
    }

    public function test_can_list_courses()
    {
        Course::factory(3)->create();

        $response = $this->getJson('/api/v1/courses');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_course()
    {
        $teacher = $this->teacherUser();
        $classRoom = ClassRoom::factory()->create();
        $teacherModel = Teacher::factory()->create();
        $subject = Subject::factory()->create();

        $data = [
            'name' => 'Mathematics 101',
            'class_room_id' => $classRoom->id,
            'teacher_id' => $teacherModel->id,
            'subject_id' => $subject->id,
        ];

        $response = $this->actingAs($teacher)->postJson('/api/v1/courses', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Mathematics 101']);
    }

    public function test_can_show_course()
    {
        $course = Course::factory()->create();

        $response = $this->getJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $course->id]);
    }

    public function test_can_update_course()
    {
        $teacher = $this->teacherUser();
        $course = Course::factory()->create();

        $data = ['name' => 'Updated Course Name'];

        $response = $this->actingAs($teacher)->putJson("/api/v1/courses/{$course->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Course Name']);
    }

    public function test_can_delete_course()
    {
        $teacher = $this->teacherUser();
        $course = Course::factory()->create();

        $response = $this->actingAs($teacher)->deleteJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
