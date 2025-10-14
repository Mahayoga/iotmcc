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
        Schema::create('log_mode_blower', function (Blueprint $table) {
            $table->uuid('id_log_mode')->primary();
            $table->enum('log_level', [1, 2, 3])->default(1);
            $table->enum('nilai_sensor', [0, 1])->default(0);
            $table->uuid('id_mode_blower');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_mode_blower')->references('id_mode_blower')->on('mode_blower')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_mode_blower');
    }
};
