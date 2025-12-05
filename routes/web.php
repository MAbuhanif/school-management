<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('students/bulk-destroy', [\App\Http\Controllers\StudentController::class, 'bulkDestroy'])->name('students.bulk-destroy');
    Route::get('students/export', [\App\Http\Controllers\StudentController::class, 'export'])->name('students.export');
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    Route::resource('courses', \App\Http\Controllers\CourseController::class);
    Route::resource('enrollments', \App\Http\Controllers\EnrollmentController::class);
    Route::get('attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('grades', [\App\Http\Controllers\GradeController::class, 'index'])->name('grades.index');
    Route::post('grades', [\App\Http\Controllers\GradeController::class, 'store'])->name('grades.store');
    Route::get('grades/{student}/report-card', [\App\Http\Controllers\GradeController::class, 'reportCard'])->name('grades.report-card');
    Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
    Route::post('payments/{invoice}/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('payments/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payments.success');
    Route::get('payments/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payments.cancel');
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
});

Route::middleware(['auth', 'role:teacher'])->post('/attendance', function () {
    return response()->json(['message' => 'Attendance marked']);
});

Route::post('stripe/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('cashier.webhook'); // Using custom handler

