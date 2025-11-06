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
        Schema::create('nilai_timer', function (Blueprint $table) {
            $table->uuid('id_nilai_timer')->primary();
            $table->enum('flag_timer', ['start', 'stop']);
            $table->char('nilai_timer', 10);
            $table->double('rssi');
            $table->double('snr');
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
        Schema::dropIfExists('nilai_timer');
    }
};
