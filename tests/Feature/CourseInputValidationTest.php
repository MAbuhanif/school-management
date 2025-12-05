<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseInputValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_description_is_sanitized()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $teacher = Teacher::factory()->create();
        $subject = Subject::factory()->create();
        $classroom = ClassRoom::factory()->create();

        $dirtyDescription = '<p>Good description</p><script>alert("XSS")</script><b>Bold</b>';
        $cleanedDescription = '<p>Good description</p>alert("XSS")<b>Bold</b>';

        $response = $this->post(route('courses.store'), [
            'name' => 'Math 101',
            'class_room_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'description' => $dirtyDescription,
        ]);

        if (session('errors')) {
            dump(session('errors')->all());
        }
        $response->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', [
            'name' => 'Math 101',
            'description' => $cleanedDescription,
        ]);
    }

    public function test_course_validation_rules()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('courses.store'), [
            'name' => '', // Required
        ]);

        $response->assertSessionHasErrors(['name', 'class_room_id', 'teacher_id', 'subject_id']);
    }
}
