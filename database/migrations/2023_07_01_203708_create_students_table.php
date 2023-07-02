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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('university_id')->references('id')->on('universities')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('faculty_id')->references('id')->on('faculties')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->references('id')->on('departments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
