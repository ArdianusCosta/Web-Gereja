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
        Schema::create('warta_jemaats', function (Blueprint $table) {
            $table->id();
            $table->string('judul_warta');
            $table->date('tanggal_warta')->nullable();
            $table->text('isi_warta');
            $table->string('lampiran_pdf')->nullable();
            $table->json('kontent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warta_jemaats');
    }
};
