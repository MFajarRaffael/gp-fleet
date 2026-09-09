<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $table = 'pics';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'jabatan',
    ];

    /**
     * Satu PIC dapat menangani banyak kendaraan.
     */
    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class, 'pic_id');
    }

    public function riwayatNotifikasis()
    {
        return $this->hasMany(RiwayatNotifikasi::class);
    }
}