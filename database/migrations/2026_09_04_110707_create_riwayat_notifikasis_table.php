<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_notifikasis', function (Blueprint $table) {

            $table->id();

            // Relasi
            $table->foreignId('dokumen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kendaraan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pic_id')->nullable()->constrained()->nullOnDelete();

            // Informasi notifikasi
            $table->string('jenis_notifikasi', 10); // H-30, H-15, H-5, H-1, H-0

            $table->enum('status', [
                'Berhasil',
                'Gagal'
            ])->default('Berhasil');

            // Waktu email dikirim
            $table->timestamp('tanggal_kirim');

            // Catatan jika suatu saat email gagal
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_notifikasis');
    }
};