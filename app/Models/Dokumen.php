<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kendaraan;

class Dokumen extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'jenis_dokumen',
        'nomor_dokumen',
        'tanggal_terbit',
        'tanggal_berlaku',
        'tanggal_expired',
        'file',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
            'tanggal_berlaku' => 'date',
            'tanggal_expired' => 'date',

            'notif_h30_sent_at' => 'datetime',
            'notif_h15_sent_at' => 'datetime',
            'notif_h5_sent_at' => 'datetime',
            'notif_h4_sent_at' => 'datetime',
            'notif_h3_sent_at' => 'datetime',
            'notif_h2_sent_at' => 'datetime',
            'notif_h1_sent_at' => 'datetime',
            'notif_h0_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function ($dokumen) {

            if ($dokumen->isDirty('tanggal_expired')) {

                $dokumen->notif_h30_sent_at = null;
                $dokumen->notif_h15_sent_at = null;
                $dokumen->notif_h5_sent_at = null;
                $dokumen->notif_h4_sent_at = null;
                $dokumen->notif_h3_sent_at = null;
                $dokumen->notif_h2_sent_at = null;
                $dokumen->notif_h1_sent_at = null;
                $dokumen->notif_h0_sent_at = null;
            }
        });
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function riwayatNotifikasis()
    {
        return $this->hasMany(RiwayatNotifikasi::class);
    }
}