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
        Schema::create('otp_rests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->string('token');
            $table->timestamp('expired');
            $table->integer('no_times_attempted')->default(0);
            $table->timestamp('generated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_rests');
    }
};
