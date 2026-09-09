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
        Schema::create('dokumens', function (Blueprint $table) {
    $table->id();

    $table->foreignId('kendaraan_id')->constrained()->onDelete('cascade');

    $table->string('jenis_dokumen');
    $table->string('nomor_dokumen');
    $table->date('tanggal_terbit');
    $table->date('tanggal_berlaku');
    $table->date('tanggal_expired');

    $table->string('file')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
