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
        Schema::create('log_sensor', function (Blueprint $table) {
            $table->uuid('id_log_sensor')->primary();
            $table->string('flag_log_sensor', 60);
            $table->enum('log_level', [1, 2, 3]);
            $table->char('nilai_sensor', 5);
            $table->uuid('id_nilai_sensor');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_nilai_sensor')->references('id_nilai_sensor')->on('nilai_sensor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_sensor');
    }
};
