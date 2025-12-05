<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\EnrollmentController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('api.')->group(function () {
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('courses', CourseController::class);

    Route::post('enrollments', [EnrollmentController::class, 'store']);
    Route::delete('enrollments/{id}', [EnrollmentController::class, 'destroy']);

    Route::post('attendances', [AttendanceController::class, 'store']);
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore']);

    Route::get('grades', [GradeController::class, 'index']);
    Route::post('grades', [GradeController::class, 'store']);

    Route::post('uploads', [\App\Http\Controllers\Api\V1\FileUploadController::class, 'upload']);
    Route::get('files/access', [\App\Http\Controllers\Api\V1\FileUploadController::class, 'access'])->name('files.access');
});
