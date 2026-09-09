<?php

namespace App\Console\Commands;

use App\Mail\DocumentExpirationMail;
use App\Models\Dokumen;
use App\Models\RiwayatNotifikasi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDocumentExpirationNotifications extends Command
{
    protected $signature = 'dokumen:notify-expired';

    protected $description = 'Kirim notifikasi dokumen berdasarkan masa berlaku';

    public function handle(): int
    {
        $today = now()->startOfDay();

        $dokumens = Dokumen::with('kendaraan.pic')
            ->whereDate('tanggal_expired', '>=', $today)
            ->whereDate('tanggal_expired', '<=', $today->copy()->addDays(30))
            ->get();

        $users = User::whereNotNull('email')->get();

        foreach ($dokumens as $dokumen) {

            // Carbon 3 mengembalikan float, jadi cast ke int
            $daysLeft = (int) $today->diffInDays(
                $dokumen->tanggal_expired->startOfDay(),
                false
            );

            // Mapping notifikasi
            $notifMap = [
                30 => [
                    'column' => 'notif_h30_sent_at',
                    'jenis' => 'H-30',
                ],
                15 => [
                    'column' => 'notif_h15_sent_at',
                    'jenis' => 'H-15',
                ],
                5 => [
                    'column' => 'notif_h5_sent_at',
                    'jenis' => 'H-5',
                ],
                4 => [
                    'column' => 'notif_h4_sent_at',
                    'jenis' => 'H-4',
                ],
                3 => [
                    'column' => 'notif_h3_sent_at',
                    'jenis' => 'H-3',
                ],
                2 => [
                    'column' => 'notif_h2_sent_at',
                    'jenis' => 'H-2',
                ],
                1 => [
                    'column' => 'notif_h1_sent_at',
                    'jenis' => 'H-1',
                ],
                0 => [
                    'column' => 'notif_h0_sent_at',
                    'jenis' => 'H-0',
                ],
            ];

            $notif = $notifMap[$daysLeft] ?? null;

            // Hari ini bukan jadwal notifikasi
            if (!$notif) {
                continue;
            }

            $column = $notif['column'];
            $jenisNotif = $notif['jenis'];

            // Sudah pernah dikirim untuk H-x ini
            if ($dokumen->$column !== null) {
                continue;
            }

            try {

                // Kirim email ke semua user yang memiliki email
                foreach ($users as $user) {
                    Mail::to($user->email)
                        ->send(new DocumentExpirationMail($dokumen));
                }

                // Tandai notifikasi sudah dikirim
                $dokumen->$column = now();
                $dokumen->save();

                // Simpan riwayat notifikasi
                RiwayatNotifikasi::create([
                    'dokumen_id' => $dokumen->id,
                    'kendaraan_id' => $dokumen->kendaraan_id,
                    'pic_id' => optional($dokumen->kendaraan)->pic_id,
                    'jenis_notifikasi' => $jenisNotif,
                    'status' => 'Berhasil',
                    'tanggal_kirim' => now(),
                    'keterangan' => 'Email berhasil dikirim.',
                ]);

                $this->info(
                    "✓ {$jenisNotif}: {$dokumen->jenis_dokumen} - Unit {$dokumen->kendaraan->nomor_unit}"
                );

            } catch (\Exception $e) {

                // Simpan histori jika email gagal
                RiwayatNotifikasi::create([
                    'dokumen_id' => $dokumen->id,
                    'kendaraan_id' => $dokumen->kendaraan_id,
                    'pic_id' => optional($dokumen->kendaraan)->pic_id,
                    'jenis_notifikasi' => $jenisNotif,
                    'status' => 'Gagal',
                    'tanggal_kirim' => now(),
                    'keterangan' => $e->getMessage(),
                ]);

                $this->error(
                    "✗ {$jenisNotif}: {$dokumen->jenis_dokumen} - Unit {$dokumen->kendaraan->nomor_unit}"
                );
            }
        }

        $this->info('Pengecekan notifikasi selesai.');

        return self::SUCCESS;
    }
}