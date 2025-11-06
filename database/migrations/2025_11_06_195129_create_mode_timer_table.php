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
        Schema::create('mode_timer', function (Blueprint $table) {
            $table->uuid('id_mode_timer')->primary();
            $table->char('limit_timer', 50);
            $table->uuid('id_sensor');
            $table->timestamps();

            $table->foreign('id_sensor')->references('id_sensor')->on('sensor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_timer');
    }
};
