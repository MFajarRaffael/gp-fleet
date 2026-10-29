<?php

namespace App\Console\Commands;

use App\Mail\DocumentExpirationMail;
use App\Models\Dokumen;
use App\Models\RiwayatNotifikasi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

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

        $recipients = User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->filter(fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        $notifMap = [
            30 => ['column' => 'notif_h30_sent_at', 'jenis' => 'H-30'],
            15 => ['column' => 'notif_h15_sent_at', 'jenis' => 'H-15'],
            5 => ['column' => 'notif_h5_sent_at', 'jenis' => 'H-5'],
            4 => ['column' => 'notif_h4_sent_at', 'jenis' => 'H-4'],
            3 => ['column' => 'notif_h3_sent_at', 'jenis' => 'H-3'],
            2 => ['column' => 'notif_h2_sent_at', 'jenis' => 'H-2'],
            1 => ['column' => 'notif_h1_sent_at', 'jenis' => 'H-1'],
            0 => ['column' => 'notif_h0_sent_at', 'jenis' => 'H-0'],
        ];

        foreach ($dokumens as $dokumen) {
            $expiryDate = $dokumen->tanggal_expired->copy()->startOfDay();

            $daysLeft = (int) $today->diffInDays($expiryDate, false);
            $notif = $notifMap[$daysLeft] ?? null;

            if (!$notif) {
                continue;
            }

            $column = $notif['column'];
            $jenisNotif = $notif['jenis'];

            if ($dokumen->$column !== null) {
                continue;
            }

            if ($recipients->isEmpty()) {
                $this->recordHistory(
                    $dokumen,
                    $jenisNotif,
                    'Gagal',
                    'Tidak ada alamat email penerima yang valid.'
                );

                $this->error("Gagal {$jenisNotif}: tidak ada penerima email valid.");
                continue;
            }

            try {
                // Satu pesan untuk semua penerima; alamat lain memakai BCC.
                $email = $recipients->values();

                Mail::to($email->shift())
                    ->bcc($email->all())
                    ->send(new DocumentExpirationMail($dokumen));
            } catch (Throwable $e) {
                Log::error('Pengiriman pengingat dokumen gagal.', [
                    'dokumen_id' => $dokumen->id,
                    'jenis_notifikasi' => $jenisNotif,
                    'exception' => $e,
                ]);

                $this->recordHistory(
                    $dokumen,
                    $jenisNotif,
                    'Gagal',
                    'Pengiriman email gagal. Periksa log aplikasi.'
                );

                $this->error("Gagal {$jenisNotif}: dokumen ID {$dokumen->id}.");
                continue;
            }

            // Tandai setelah mailer menerima pengiriman dengan sukses.
            // Ini bukan jaminan email sudah masuk ke kotak masuk penerima.
            $dokumen->$column = now();

            try {
                $dokumen->save();
            } catch (Throwable $e) {
                Log::critical('Email terkirim, tetapi status notifikasi gagal disimpan.', [
                    'dokumen_id' => $dokumen->id,
                    'jenis_notifikasi' => $jenisNotif,
                    'exception' => $e,
                ]);

                $this->error(
                    "Email diproses, tetapi status dokumen ID {$dokumen->id} gagal disimpan."
                );

                continue;
            }

            $historySaved = $this->recordHistory(
                $dokumen,
                $jenisNotif,
                'Berhasil',
                'Pengiriman email diterima oleh mailer untuk '
                . ($recipients->count()) . ' penerima.'
            );

            if ($historySaved) {
                $this->info(
                    "Berhasil {$jenisNotif}: dokumen ID {$dokumen->id}."
                );
            }
        }

        $this->info('Pengecekan notifikasi selesai.');

        return self::SUCCESS;
    }

    private function recordHistory(
        Dokumen $dokumen,
        string $jenisNotif,
        string $status,
        string $keterangan
    ): bool {
        try {
            RiwayatNotifikasi::create([
                'dokumen_id' => $dokumen->id,
                'kendaraan_id' => $dokumen->kendaraan_id,
                'pic_id' => $dokumen->kendaraan?->pic_id,
                'jenis_notifikasi' => $jenisNotif,
                'status' => $status,
                'tanggal_kirim' => now(),
                'keterangan' => $keterangan,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::error('Riwayat notifikasi gagal disimpan.', [
                'dokumen_id' => $dokumen->id,
                'jenis_notifikasi' => $jenisNotif,
                'status' => $status,
                'exception' => $e,
            ]);

            $this->error(
                "Riwayat {$jenisNotif} untuk dokumen ID {$dokumen->id} gagal disimpan."
            );

            return false;
        }
    }
}
