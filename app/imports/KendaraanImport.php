<?php

namespace App\Imports;

use App\Models\Kendaraan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KendaraanImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Kendaraan' => new KendaraanSheetImport('Kendaraan'),
            'HV' => new KendaraanSheetImport('HV'),
            'HE' => new KendaraanSheetImport('HE'),
        ];
    }
}

class KendaraanSheetImport implements ToCollection
{
    protected string $kategori;

    public function __construct(string $kategori)
    {
        $this->kategori = $kategori;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {

            $nomorUnit = trim((string) ($row[5] ?? ''));

            // Lewati baris kosong
            if ($nomorUnit === '') {
                continue;
            }

            // Sheet Kendaraan mengambil tipe dari Excel.
            // Sheet HV dan HE otomatis menggunakan kategorinya.
            $tipe = $this->kategori === 'Kendaraan'
                ? trim((string) ($row[1] ?? ''))
                : $this->kategori;

            $merk = trim((string) ($row[2] ?? ''));
            $plat = trim((string) ($row[3] ?? ''));
            $project = trim((string) ($row[4] ?? ''));
            $tahun = $row[6] ?? null;

            $merk = $merk !== '' ? $merk : null;
            $plat = $plat !== '' ? $plat : null;
            $project = $project !== '' ? $project : null;

            if ($tahun !== null && $tahun !== '') {
                $tahun = (int) $tahun;
            } else {
                $tahun = null;
            }

            // Jangan masukkan nomor unit yang sudah ada.
            if (Kendaraan::where('nomor_unit', $nomorUnit)->exists()) {
                continue;
            }

            Kendaraan::create([
                'kategori' => $this->kategori,
                'tipe' => $tipe ?: $this->kategori,
                'plat_nomor' => $plat,
                'nomor_unit' => $nomorUnit,
                'merk' => $merk,
                'tahun' => $tahun,
                'project' => $project,
                'status' => 'Aktif',
            ]);
        }
    }
}