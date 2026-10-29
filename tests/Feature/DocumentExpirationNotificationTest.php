<?php

use App\Mail\DocumentExpirationMail;
use App\Models\Dokumen;
use App\Models\Kendaraan;
use App\Models\RiwayatNotifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();

    $this->travelTo(now()->setTime(8, 0, 0));
});

afterEach(function () {
    $this->travelBack();
});

function createDokumenUntukPengingat(int $daysLeft): Dokumen
{
    $kendaraan = Kendaraan::create([
        'kategori' => 'Kendaraan',
        'tipe' => 'Mobil',
        'nomor_unit' => 'TEST-' . uniqid(),
        'status' => 'Aktif',
    ]);

    return Dokumen::create([
        'kendaraan_id' => $kendaraan->id,
        'jenis_dokumen' => 'STNK',
        'nomor_dokumen' => 'TEST-' . uniqid(),
        'tanggal_terbit' => now()->subYear()->toDateString(),
        'tanggal_berlaku' => now()->toDateString(),
        'tanggal_expired' => now()->addDays($daysLeft)->toDateString(),
    ]);
}

it('mengirim pengingat H-30 dan mencatat riwayat berhasil', function () {
    User::factory()->create();

    $dokumen = createDokumenUntukPengingat(30);

    $this->artisan('dokumen:notify-expired')->assertSuccessful();

    Mail::assertSent(DocumentExpirationMail::class, 1);

    expect($dokumen->fresh()->notif_h30_sent_at)->not->toBeNull();

    $this->assertDatabaseHas('riwayat_notifikasis', [
        'dokumen_id' => $dokumen->id,
        'jenis_notifikasi' => 'H-30',
        'status' => 'Berhasil',
    ]);
});

it('tidak mengirim pengingat dua kali untuk jadwal yang sama', function () {
    User::factory()->create();

    $dokumen = createDokumenUntukPengingat(30);

    $this->artisan('dokumen:notify-expired')->assertSuccessful();
    $this->artisan('dokumen:notify-expired')->assertSuccessful();

    Mail::assertSent(DocumentExpirationMail::class, 1);

    expect(
        RiwayatNotifikasi::where('dokumen_id', $dokumen->id)
            ->where('jenis_notifikasi', 'H-30')
            ->count()
    )->toBe(1);
});

it('tidak mengirim pengingat pada hari yang bukan jadwalnya', function () {
    User::factory()->create();

    createDokumenUntukPengingat(20);

    $this->artisan('dokumen:notify-expired')->assertSuccessful();

    Mail::assertNothingSent();
});

it('tidak menandai pengingat berhasil jika pengiriman email gagal', function () {
    User::factory()->create();

    $dokumen = createDokumenUntukPengingat(30);

    Mail::shouldReceive('to')
        ->once()
        ->andThrow(new RuntimeException('Simulasi kegagalan email'));

    $this->artisan('dokumen:notify-expired')->assertSuccessful();

    expect($dokumen->fresh()->notif_h30_sent_at)->toBeNull();

    $this->assertDatabaseHas('riwayat_notifikasis', [
        'dokumen_id' => $dokumen->id,
        'jenis_notifikasi' => 'H-30',
        'status' => 'Gagal',
    ]);
});

it('mereset penanda pengiriman saat tanggal kedaluwarsa berubah', function () {
    $dokumen = createDokumenUntukPengingat(30);

    $dokumen->notif_h30_sent_at = now();
    $dokumen->save();

    $dokumen->tanggal_expired = now()->addDays(45)->toDateString();
    $dokumen->save();

    expect($dokumen->fresh()->notif_h30_sent_at)->toBeNull();
});
