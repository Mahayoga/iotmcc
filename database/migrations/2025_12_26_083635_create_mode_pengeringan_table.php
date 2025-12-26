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
        Schema::create('mode_pengeringan', function (Blueprint $table) {
            $table->uuid('id_mode_pengeringan')->primary();
            $table->enum('mode_pengeringan', [0, 1]);
            $table->integer('min_suhu');
            $table->integer('max_suhu');
            $table->integer('min_kelembaban');
            $table->integer('max_kelembaban');
            $table->uuid('id_ruangan');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_pengeringan');
    }
};
