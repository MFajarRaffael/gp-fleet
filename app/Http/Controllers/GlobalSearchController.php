<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Kendaraan;
use App\Models\Pic;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->q);

        if (!$keyword) {
            return response()->json([]);
        }

        $kendaraans = Kendaraan::with('pic')
            ->where(function ($query) use ($keyword) {
                $query->where('nomor_unit', 'like', "%{$keyword}%")
                    ->orWhere('plat_nomor', 'like', "%{$keyword}%")
                    ->orWhere('merk', 'like', "%{$keyword}%")
                    ->orWhere('tipe', 'like', "%{$keyword}%")
                    ->orWhere('project', 'like', "%{$keyword}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'kendaraan',
                    'title' => $item->plat_nomor ?: '-',
                    'subtitle' => 'Unit ' . $item->nomor_unit,
                    'url' => route('kendaraan.show', $item->id),
                ];
            });

        $pics = Pic::where('nama', 'like', "%{$keyword}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pic',
                    'title' => $item->nama,
                    'subtitle' => $item->jabatan ?: 'PIC Green Planet',
                    'url' => route('pic.show', $item->id),
                ];
            });

        $dokumens = Dokumen::with('kendaraan')
            ->where(function ($query) use ($keyword) {
                $query->where('jenis_dokumen', 'like', "%{$keyword}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$keyword}%")
                    ->orWhereHas('kendaraan', function ($kendaraan) use ($keyword) {
                        $kendaraan->where('nomor_unit', 'like', "%{$keyword}%")
                            ->orWhere('plat_nomor', 'like', "%{$keyword}%");
                    });
            })
            ->limit(5)
            ->get()
            ->map(function ($item) {

                $plate = optional($item->kendaraan)->plat_nomor ?? '-';

                return [
                    'type' => 'dokumen',
                    'title' => $item->jenis_dokumen,
                    'subtitle' => $plate,
                    'url' => route('dokumen.index', [
                        'search' => $item->nomor_dokumen
                    ]),
                ];
            });

        return response()->json([
            'kendaraan' => $kendaraans,
            'pic' => $pics,
            'dokumen' => $dokumens,
        ]);
    }
}