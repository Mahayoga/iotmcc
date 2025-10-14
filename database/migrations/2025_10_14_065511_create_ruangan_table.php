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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->uuid('id_ruangan')->primary();
            $table->string('nama_ruangan', 60);
            $table->enum('tipe_ruangan', [1, 2, 3])->default(1);
            $table->enum('status_ruangan', [1, 2, 3]);
            $table->uuid('id_gudang');
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_gudang')->references('id_gudang')->on('gudang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
