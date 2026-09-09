<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();

            $table->enum('kategori', ['Kendaraan', 'HV', 'HE']);

            $table->string('tipe');

            $table->string('plat_nomor')->nullable();

            $table->string('nomor_unit')->unique();

            $table->string('merk')->nullable();

            $table->year('tahun')->nullable();

            $table->string('project')->nullable();

            $table->enum('status', ['Aktif', 'Tidak Aktif'])
                ->default('Aktif');

            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};