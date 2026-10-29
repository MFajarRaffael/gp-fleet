<?php

use App\Models\Dokumen;
use App\Models\Kendaraan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

beforeEach(function () {
    $this->user = User::factory()->create();
});

function dataKendaraanTes(array $override = []): array
{
    return array_merge([
        'kategori' => 'Kendaraan',
        'tipe' => 'Pickup',
        'plat_nomor' => 'BM1234XX',
        'nomor_unit' => 'UNIT-TEST-001',
        'merk' => 'Toyota',
        'tahun' => 2024,
        'project' => 'Pengujian',
        'status' => 'Aktif',
    ], $override);
}

function dataDokumenTes(int $kendaraanId, array $override = []): array
{
    return array_merge([
        'kendaraan_id' => $kendaraanId,
        'jenis_dokumen' => 'STNK',
        'nomor_dokumen' => 'DOC-TEST-001',
        'tanggal_terbit' => '2026-01-01',
        'tanggal_berlaku' => '2026-01-01',
        'tanggal_expired' => '2027-01-01',
    ], $override);
}

it('menyimpan kendaraan baru', function () {
    actingAs($this->user)
        ->post(route('kendaraan.store'), dataKendaraanTes())
        ->assertRedirect(route('kendaraan.index'));

    $this->assertDatabaseHas('kendaraans', [
        'nomor_unit' => 'UNIT-TEST-001',
        'merk' => 'Toyota',
    ]);
});

it('menolak kendaraan tanpa nomor unit', function () {
    actingAs($this->user)
        ->post(route('kendaraan.store'), dataKendaraanTes([
            'nomor_unit' => '',
        ]))
        ->assertSessionHasErrors('nomor_unit');

    $this->assertDatabaseMissing('kendaraans', [
        'nomor_unit' => 'UNIT-TEST-001',
    ]);
});

it('memperbarui kendaraan', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    actingAs($this->user)
        ->put(route('kendaraan.update', $kendaraan), dataKendaraanTes([
            'nomor_unit' => 'UNIT-TEST-UPDATED',
            'plat_nomor' => 'BM5678XX',
        ]))
        ->assertRedirect(route('kendaraan.index', [
            'kategori' => 'Kendaraan',
        ]));

    $this->assertDatabaseHas('kendaraans', [
        'id' => $kendaraan->id,
        'nomor_unit' => 'UNIT-TEST-UPDATED',
        'plat_nomor' => 'BM5678XX',
    ]);
});

it('menghapus kendaraan', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    actingAs($this->user)
        ->delete(route('kendaraan.destroy', $kendaraan))
        ->assertRedirect(route('kendaraan.index'));

    $this->assertDatabaseMissing('kendaraans', [
        'id' => $kendaraan->id,
    ]);
});

it('menyimpan dokumen kendaraan', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    actingAs($this->user)
        ->post(route('dokumen.store'), dataDokumenTes($kendaraan->id))
        ->assertRedirect(route('dokumen.index'));

    $this->assertDatabaseHas('dokumens', [
        'kendaraan_id' => $kendaraan->id,
        'nomor_dokumen' => 'DOC-TEST-001',
        'jenis_dokumen' => 'STNK',
    ]);
});

it('menyimpan jenis Dokumen Lainnya menggunakan nama yang diisi', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    actingAs($this->user)
        ->post(route('dokumen.store'), dataDokumenTes($kendaraan->id, [
            'jenis_dokumen' => 'Dokumen Lainnya',
            'jenis_dokumen_lainnya' => 'Surat Pengujian',
        ]))
        ->assertRedirect(route('dokumen.index'));

    $this->assertDatabaseHas('dokumens', [
        'kendaraan_id' => $kendaraan->id,
        'jenis_dokumen' => 'Surat Pengujian',
    ]);
});

it('menolak dokumen dengan tanggal expired sebelum tanggal berlaku', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    actingAs($this->user)
        ->post(route('dokumen.store'), dataDokumenTes($kendaraan->id, [
            'tanggal_berlaku' => '2026-06-01',
            'tanggal_expired' => '2026-05-01',
        ]))
        ->assertSessionHasErrors('tanggal_expired');

    $this->assertDatabaseMissing('dokumens', [
        'nomor_dokumen' => 'DOC-TEST-001',
    ]);
});

it('memperbarui dokumen', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());
    $dokumen = Dokumen::create(dataDokumenTes($kendaraan->id));

    actingAs($this->user)
        ->put(route('dokumen.update', $dokumen), dataDokumenTes($kendaraan->id, [
            'nomor_dokumen' => 'DOC-TEST-UPDATED',
        ]))
        ->assertRedirect(route('dokumen.index'));

    $this->assertDatabaseHas('dokumens', [
        'id' => $dokumen->id,
        'nomor_dokumen' => 'DOC-TEST-UPDATED',
    ]);
});

it('menghapus dokumen', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());
    $dokumen = Dokumen::create(dataDokumenTes($kendaraan->id));

    actingAs($this->user)
        ->delete(route('dokumen.destroy', $dokumen))
        ->assertRedirect(route('dokumen.index'));

    $this->assertDatabaseMissing('dokumens', [
        'id' => $dokumen->id,
    ]);
});


it('memfilter kendaraan berdasarkan kategori', function () {
    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-FILTER-HV',
        'kategori' => 'HV',
    ]));

    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-FILTER-HE',
        'kategori' => 'HE',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.index', ['kategori' => 'HV']));

    $response->assertOk();

    $response->assertViewHas('kendaraans', function ($kendaraans) {
        return $kendaraans->total() === 1
            && $kendaraans->first()->nomor_unit === 'UNIT-FILTER-HV';
    });
});

it('mencari kendaraan berdasarkan nomor unit', function () {
    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-CARI-001',
    ]));

    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-LAIN-002',
        'plat_nomor' => 'BM9999ZZ',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.index', ['search' => 'UNIT-CARI-001']));

    $response->assertOk();

    $response->assertViewHas('kendaraans', function ($kendaraans) {
        return $kendaraans->total() === 1
            && $kendaraans->first()->nomor_unit === 'UNIT-CARI-001';
    });
});

it('mencari dokumen berdasarkan nomor dokumen', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-CARI-001',
    ]));

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-LAIN-002',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('dokumen.index', ['search' => 'DOC-CARI-001']));

    $response->assertOk();

    $response->assertViewHas('dokumens', function ($dokumens) {
        return $dokumens->total() === 1
            && $dokumens->first()->nomor_dokumen === 'DOC-CARI-001';
    });
});

it('memfilter dokumen berdasarkan jenisnya', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-STNK-001',
        'jenis_dokumen' => 'STNK',
    ]));

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-KIR-002',
        'jenis_dokumen' => 'KIR',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('dokumen.index', ['jenis' => 'STNK']));

    $response->assertOk();

    $response->assertViewHas('dokumens', function ($dokumens) {
        return $dokumens->total() === 1
            && $dokumens->first()->jenis_dokumen === 'STNK';
    });
});


use Illuminate\Http\UploadedFile;

it('mengekspor kendaraan ke CSV', function () {
    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-EXPORT-001',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.export', [
            'format' => 'csv',
            'scope' => 'all',
        ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertHeader(
        'content-disposition',
        'attachment; filename=data-kendaraan.csv'
    );
});

it('menolak format ekspor kendaraan yang tidak didukung', function () {
    $this->actingAs($this->user)
        ->get(route('kendaraan.export', [
            'format' => 'txt',
            'scope' => 'all',
        ]))
        ->assertBadRequest();
});

it('menolak ekspor kendaraan terpilih tanpa ID', function () {
    $this->actingAs($this->user)
        ->get(route('kendaraan.export', [
            'format' => 'csv',
            'scope' => 'selected',
        ]))
        ->assertSessionHasErrors('ids');
});

it('mengekspor dokumen ke CSV', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-EXPORT-001',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('dokumen.export', [
            'format' => 'csv',
        ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertHeader('content-disposition');
});

it('menolak format ekspor dokumen yang tidak didukung', function () {
    $this->actingAs($this->user)
        ->get(route('dokumen.export', [
            'format' => 'txt',
        ]))
        ->assertBadRequest();
});

it('menolak impor kendaraan dengan ekstensi file yang tidak didukung', function () {
    $file = UploadedFile::fake()->create(
        'kendaraan.txt',
        10,
        'text/plain'
    );

    $this->actingAs($this->user)
        ->post(route('kendaraan.import'), [
            'file' => $file,
        ])
        ->assertSessionHasErrors('file');
});


it('mengekspor kendaraan ke Excel', function () {
    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-EXCEL-001',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.export', [
            'format' => 'xlsx',
            'scope' => 'all',
        ]));

    $response->assertOk();
    $response->assertHeader('content-disposition');
});

it('mengekspor kendaraan ke PDF', function () {
    Kendaraan::create(dataKendaraanTes([
        'nomor_unit' => 'UNIT-PDF-001',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.export', [
            'format' => 'pdf',
            'scope' => 'all',
        ]));

    $response->assertOk();
    $response->assertHeader('content-disposition');
});

it('mengekspor detail kendaraan ke CSV', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    Dokumen::create(dataDokumenTes($kendaraan->id, [
        'nomor_dokumen' => 'DOC-DETAIL-001',
    ]));

    $response = $this->actingAs($this->user)
        ->get(route('kendaraan.exportDetail', [
            'kendaraan' => $kendaraan->id,
            'format' => 'csv',
        ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertHeader('content-disposition');
});

it('menolak format ekspor detail kendaraan yang tidak didukung', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());

    $this->actingAs($this->user)
        ->get(route('kendaraan.exportDetail', [
            'kendaraan' => $kendaraan->id,
            'format' => 'txt',
        ]))
        ->assertBadRequest();
});

it('mengekspor dokumen ke Excel', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());
    Dokumen::create(dataDokumenTes($kendaraan->id));

    $response = $this->actingAs($this->user)
        ->get(route('dokumen.export', ['format' => 'xlsx']));

    $response->assertOk();
    $response->assertHeader('content-disposition');
});

it('mengekspor dokumen ke PDF', function () {
    $kendaraan = Kendaraan::create(dataKendaraanTes());
    Dokumen::create(dataDokumenTes($kendaraan->id));

    $response = $this->actingAs($this->user)
        ->get(route('dokumen.export', ['format' => 'pdf']));

    $response->assertOk();
    $response->assertHeader('content-disposition');
});


it('mengimpor kendaraan dari file Excel yang valid', function () {
    $file = UploadedFile::fake()->create(
        'kendaraan.xlsx',
        10,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    $this->actingAs($this->user)
        ->post(route('kendaraan.import'), [
            'file' => $file,
        ])
        ->assertRedirect(route('kendaraan.index'))
        ->assertSessionHas('error');
});


it('mengimpor kendaraan dari workbook Excel dengan tiga sheet', function () {
    Excel::fake();

    $file = UploadedFile::fake()->create(
        'kendaraan.xlsx',
        10,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );

    $this->actingAs($this->user)
        ->post(route('kendaraan.import'), [
            'file' => $file,
        ])
        ->assertRedirect(route('kendaraan.index'))
        ->assertSessionHas('success');

    Excel::assertImported('kendaraan.xlsx', function ($import) {
        return $import instanceof \App\Imports\KendaraanImport
            && array_keys($import->sheets()) === ['Kendaraan', 'HV', 'HE'];
    });
});

it('menolak foto kendaraan dengan format yang tidak didukung', function () {
    $foto = UploadedFile::fake()->create(
        'foto.gif',
        10,
        'image/gif'
    );

    $this->actingAs($this->user)
        ->post(route('kendaraan.store'), [
            'foto' => $foto,
            'kategori' => 'Kendaraan',
            'tipe' => 'Mobil',
            'nomor_unit' => 'UNIT-TEST-FOTO-001',
            'merk' => 'Toyota',
            'status' => 'Aktif',
        ])
        ->assertSessionHasErrors('foto');

    $this->assertDatabaseMissing('kendaraans', [
        'nomor_unit' => 'UNIT-TEST-FOTO-001',
    ]);
});

it('menolak foto kendaraan yang melebihi batas ukuran', function () {
    $foto = UploadedFile::fake()->image('foto.jpg')->size(2049);

    $this->actingAs($this->user)
        ->post(route('kendaraan.store'), [
            'foto' => $foto,
            'kategori' => 'Kendaraan',
            'tipe' => 'Mobil',
            'nomor_unit' => 'UNIT-TEST-FOTO-002',
            'merk' => 'Toyota',
            'status' => 'Aktif',
        ])
        ->assertSessionHasErrors('foto');

    $this->assertDatabaseMissing('kendaraans', [
        'nomor_unit' => 'UNIT-TEST-FOTO-002',
    ]);
});

it('menolak file dokumen dengan format yang tidak didukung', function () {
    $kendaraan = Kendaraan::create([
        'kategori' => 'Kendaraan',
        'tipe' => 'Mobil',
        'nomor_unit' => 'UNIT-DOC-TEST-001',
        'merk' => 'Toyota',
        'status' => 'Aktif',
    ]);

    $file = UploadedFile::fake()->create(
        'dokumen.exe',
        10,
        'application/octet-stream'
    );

    $this->actingAs($this->user)
        ->post(route('dokumen.store'), [
            'kendaraan_id' => $kendaraan->id,
            'jenis_dokumen' => 'STNK',
            'nomor_dokumen' => 'DOC-TEST-001',
            'tanggal_terbit' => '2026-01-01',
            'tanggal_berlaku' => '2026-01-01',
            'tanggal_expired' => '2027-01-01',
            'file' => $file,
        ])
        ->assertSessionHasErrors('file');
});

it('menolak file dokumen yang melebihi batas ukuran', function () {
    $kendaraan = Kendaraan::create([
        'kategori' => 'Kendaraan',
        'tipe' => 'Mobil',
        'nomor_unit' => 'UNIT-DOC-TEST-002',
        'merk' => 'Toyota',
        'status' => 'Aktif',
    ]);

    $file = UploadedFile::fake()->create(
        'dokumen.pdf',
        20481,
        'application/pdf'
    );

    $this->actingAs($this->user)
        ->post(route('dokumen.store'), [
            'kendaraan_id' => $kendaraan->id,
            'jenis_dokumen' => 'STNK',
            'nomor_dokumen' => 'DOC-TEST-002',
            'tanggal_terbit' => '2026-01-01',
            'tanggal_berlaku' => '2026-01-01',
            'tanggal_expired' => '2027-01-01',
            'file' => $file,
        ])
        ->assertSessionHasErrors('file');
});