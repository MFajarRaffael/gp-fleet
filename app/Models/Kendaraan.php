<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans';

    protected $fillable = [
        'foto',
        'kategori',
        'tipe',
        'plat_nomor',
        'nomor_unit',
        'pic_id',
        'merk',
        'tahun',
        'project',
        'status',
    ];

    /**
     * Kendaraan dimiliki oleh satu PIC.
     */
    public function pic()
    {
        return $this->belongsTo(Pic::class, 'pic_id');
    }

    /**
     * Kendaraan memiliki banyak dokumen.
     */
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'kendaraan_id');
    }

    /**
     * Kendaraan memiliki banyak riwayat maintenance.
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'kendaraan_id');
    }

    public function riwayatNotifikasis()
    {
        return $this->hasMany(RiwayatNotifikasi::class);
    }
}