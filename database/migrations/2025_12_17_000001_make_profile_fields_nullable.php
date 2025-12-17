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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('class_room_id')->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->string('qualification')->nullable()->change();
            $table->string('specialization')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this is tricky without knowing original state perfectly,
        // but generally we'd make them required. Skipped for safety in dev.
    }
};
