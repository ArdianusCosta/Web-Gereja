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
        Schema::create('jadwal_ibadahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gereja_id')->constrained('gerejas')->onDelete('cascade');
            $table->string('nama_ibadah');
            $table->enum('hari',['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'])->nullable();
            $table->date('tanggal')->nullable();
            $table->time('waktu')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ibadahs');
    }
};
