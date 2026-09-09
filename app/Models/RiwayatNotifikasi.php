<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatNotifikasi extends Model
{
    protected $fillable = [
        'dokumen_id',
        'kendaraan_id',
        'pic_id',
        'jenis_notifikasi',
        'status',
        'tanggal_kirim',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kirim' => 'datetime',
    ];

    // Relasi ke Dokumen
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    // Relasi ke Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Relasi ke PIC
    public function pic()
    {
        return $this->belongsTo(Pic::class);
    }
}