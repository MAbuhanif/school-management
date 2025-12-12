<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Grade;
use App\Models\Notice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Create Admin (handled in RoleSeeder)
        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@school.com',
        //     // 'role' => 'admin', // Role assigned via seeder if needed, or we use the one from RoleSeeder
        // ]);

        // Create Teachers
        $teachers = Teacher::factory(5)->create()->each(function ($teacher) {
            $teacher->user->assignRole('teacher');
        });

        // Create Subjects
        $subjects = Subject::factory(5)->create();

        // Create ClassRooms
        $classRooms = ClassRoom::factory(5)->create();

        // Create Students
        $students = Student::factory(20)->recycle($classRooms)->create()->each(function ($student) {
            $student->user->assignRole('student');
        });

        // Create Courses
        $courses = Course::factory(10)
            ->recycle($teachers)
            ->recycle($subjects)
            ->recycle($classRooms)
            ->create();

        // Create Enrollments
        Enrollment::factory(50)
            ->recycle($students)
            ->recycle($courses)
            ->create();

        // Create Attendances
        Attendance::factory(100)
            ->recycle($students)
            ->recycle($courses)
            ->create();

        // Create Grades
        Grade::factory(100)
            ->recycle($students)
            ->recycle($courses)
            ->create();

        // Create Fees and Payments
        $fees = Fee::factory(30)
            ->recycle($students)
            ->create();

        Payment::factory(20)
            ->recycle($fees)
            ->create();

        // Create Invoices
        \App\Models\Invoice::factory(20)
            ->recycle($students)
            ->create();

        // Create Notices
        Notice::factory(10)->create();
    }
}
