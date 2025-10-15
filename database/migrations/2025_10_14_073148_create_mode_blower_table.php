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
        Schema::create('mode_blower', function (Blueprint $table) {
            $table->uuid('id_mode_blower')->primary();
            $table->enum('nilai_sensor', [0, 1])->default(0);
            $table->uuid('id_sensor');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_sensor')->references('id_sensor')->on('sensor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_blower');
    }
};
