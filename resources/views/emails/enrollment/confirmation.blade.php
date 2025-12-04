<x-mail::message>
# Enrollment Confirmation

Dear {{ $enrollment->student->user->name }},

You have successfully enrolled in **{{ $enrollment->course->name }}**.

**Course Details:**
- **Teacher:** {{ $enrollment->course->teacher->user->name }}
- **Start Date:** {{ $enrollment->enrolled_at->format('F j, Y') }}

<x-mail::button :url="config('app.url')">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
