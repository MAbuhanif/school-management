<?php

namespace Tests\Feature\Api\V1;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students()
    {
        Student::factory(3)->create();

        $response = $this->getJson('/api/v1/students');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_student()
    {
        $user = User::factory()->create();
        $classRoom = ClassRoom::factory()->create();

        $data = [
            'user_id' => $user->id,
            'class_room_id' => $classRoom->id,
            'dob' => '2005-01-01',
            'gender' => 'male',
            'address' => '123 Main St',
            'phone' => '1234567890',
        ];

        $response = $this->postJson('/api/v1/students', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['address' => '123 Main St']);
    }

    public function test_can_show_student()
    {
        $student = Student::factory()->create();

        $response = $this->getJson("/api/v1/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $student->id]);
    }

    public function test_can_update_student()
    {
        $student = Student::factory()->create();

        $data = ['address' => 'Updated Address'];

        $response = $this->putJson("/api/v1/students/{$student->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['address' => 'Updated Address']);
    }

    public function test_can_delete_student()
    {
        $student = Student::factory()->create();

        $response = $this->deleteJson("/api/v1/students/{$student->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
