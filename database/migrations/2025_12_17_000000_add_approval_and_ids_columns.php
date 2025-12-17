<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('email_verified_at');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id')->unique()->nullable()->after('user_id');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->string('employee_id')->unique()->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
};
