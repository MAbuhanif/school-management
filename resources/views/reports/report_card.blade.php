<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .student-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Report Card</h1>
        <h3>{{ config('app.name') }}</h3>
    </div>

    <div class="student-info">
        <p><strong>Name:</strong> {{ $student->user->name }}</p>
        <p><strong>Class:</strong> {{ $student->classRoom ? $student->classRoom->name : 'N/A' }}</p>
        <p><strong>Attendance:</strong> {{ number_format($attendancePercentage, 1) }}% Present</p>
    </div>

    <h2>Grades</h2>
    @foreach($gradesByCourse as $courseName => $grades)
        <h3>{{ $courseName }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Assessment</th>
                    <th>Score</th>
                    <th>Max Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->assessment_name }}</td>
                        <td>{{ $grade->score }}</td>
                        <td>{{ $grade->max_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
