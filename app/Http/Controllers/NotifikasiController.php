<?php

namespace App\Http\Controllers;

use App\Models\RiwayatNotifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatNotifikasi::with([
            'dokumen',
            'kendaraan',
            'pic'
        ])->latest('tanggal_kirim');

        // ===========================
        // FILTER STATUS
        // ===========================
        if ($request->filled('status')) {

            if ($request->status == 'expired') {

                $query->where('jenis_notifikasi', 'H-0');

            } elseif ($request->status == 'warning') {

                $query->whereIn('jenis_notifikasi', [
                    'H-30',
                    'H-15',
                    'H-5',
                    'H-4',
                    'H-3',
                    'H-2',
                    'H-1'
                ]);
            }
        }

        // ===========================
        // FILTER JENIS NOTIFIKASI
        // ===========================
        if ($request->filled('jenis')) {
            $query->where('jenis_notifikasi', $request->jenis);
        }

        // ===========================
        // SEARCH UNIT / PLAT / DOKUMEN
        // ===========================
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('kendaraan', function ($kendaraan) use ($search) {

                    $kendaraan->where('nomor_unit', 'like', "%{$search}%")
                        ->orWhere('plat_nomor', 'like', "%{$search}%");

                });

                $q->orWhereHas('dokumen', function ($dokumen) use ($search) {

                    $dokumen->where('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('jenis_dokumen', 'like', "%{$search}%");

                });

                $q->orWhereHas('pic', function ($pic) use ($search) {

                    $pic->where('nama', 'like', "%{$search}%");

                });

            });
        }

        // ===========================
        // FILTER TANGGAL
        // ===========================
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kirim', $request->tanggal);
        }

        $notifikasis = $query->paginate(10)->withQueryString();

        // ===========================
        // SUMMARY CARD
        // ===========================
        $summary = [
            'total' => RiwayatNotifikasi::count(),

            'hari_ini' => RiwayatNotifikasi::whereDate(
                'tanggal_kirim',
                today()
            )->count(),

            'warning' => RiwayatNotifikasi::whereIn('jenis_notifikasi', [
                'H-30',
                'H-15',
                'H-5',
                'H-4',
                'H-3',
                'H-2',
                'H-1',
            ])->count(),

            'expired' => RiwayatNotifikasi::where(
                'jenis_notifikasi',
                'H-0'
            )->count(),
        ];

        return view('notifikasi.index', compact(
            'notifikasis',
            'summary'
        ));
    }
}