<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_course()
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $user = User::where('email', 'superadmin@school.com')->first();
        
        $classRoom = ClassRoom::factory()->create();
        $teacher = Teacher::factory()->create();
        $subject = Subject::factory()->create();

        $response = $this->actingAs($user)->post(route('courses.store'), [
            'name' => 'New Course',
            'description' => 'Description',
            'class_room_id' => $classRoom->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', [
            'name' => 'New Course',
        ]);
    }
}
