<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KendaraanExport implements FromCollection, WithHeadings
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nomor Unit',
            'Kategori',
            'Plat Nomor',
            'Merk',
            'Tipe',
            'Tahun',
            'Project',
            'PIC',
            'Status',
        ];
    }
}
