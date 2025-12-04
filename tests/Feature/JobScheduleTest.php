<?php

namespace Tests\Feature;

use App\Jobs\ImportStudentsJob;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class JobScheduleTest extends TestCase
{
    public function test_import_students_job_can_be_dispatched()
    {
        Bus::fake();

        ImportStudentsJob::dispatch(['name' => 'John Doe']);

        Bus::assertDispatched(ImportStudentsJob::class, function ($job) {
            return $job->data['name'] === 'John Doe';
        });
    }

    public function test_schedule_contains_inspire_command()
    {
        $schedule = app(Schedule::class);

        $events = collect($schedule->events())->filter(function (Event $event) {
            return stripos($event->command, 'inspire');
        });

        $this->assertTrue($events->count() > 0);
    }
}
