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
        Schema::create('riwayat_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_sensor')->nullable();
            $table->string('title');
            $table->string('body');
            $table->string('kategori')->default('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_notifikasi');
    }
};
