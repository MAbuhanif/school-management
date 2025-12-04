<?php

namespace Tests\Feature;

use App\Mail\EnrollmentConfirmation;
use App\Mail\FeeInvoice;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AttendanceAlert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_confirmation_mailable_content()
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $mailable = new EnrollmentConfirmation($enrollment);

        $mailable->assertSeeInHtml($student->user->name);
        $mailable->assertSeeInHtml($course->name);
    }

    public function test_fee_invoice_mailable_content()
    {
        $student = Student::factory()->create();
        $fee = Fee::factory()->create([
            'student_id' => $student->id,
            'amount' => 100.00,
            'type' => 'tuition',
        ]);

        $mailable = new FeeInvoice($fee);

        $mailable->assertSeeInHtml($student->user->name);
        $mailable->assertSeeInHtml('100.00');
        $mailable->assertSeeInHtml('Tuition');
    }

    public function test_attendance_alert_notification()
    {
        Notification::fake();

        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $attendance = Attendance::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'absent',
        ]);

        $user = User::factory()->create();

        $user->notify(new AttendanceAlert($attendance));

        Notification::assertSentTo(
            [$user],
            AttendanceAlert::class,
            function ($notification, $channels) use ($attendance) {
                $mailData = $notification->toMail($notification);
                $this->assertStringContainsString('absent', $mailData->introLines[0]);
                $this->assertStringContainsString($attendance->course->name, $mailData->introLines[0]);
                return true;
            }
        );
    }
}
