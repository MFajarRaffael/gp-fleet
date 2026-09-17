<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DokumenController extends Controller
{
    /**
     * Menampilkan semua dokumen.
     */
    public function index(Request $request)
    {
        $query = Dokumen::with('kendaraan');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhere('jenis_dokumen', 'like', "%{$search}%") // tambah ini
                    ->orWhereHas('kendaraan', function ($kendaraan) use ($search) {

                        $kendaraan->where('nomor_unit', 'like', "%{$search}%")
                            ->orWhere('plat_nomor', 'like', "%{$search}%")
                            ->orWhere('tipe', 'like', "%{$search}%");

                    });

            });
        }

        // Jenis Dokumen
        if ($request->filled('jenis')) {
            $query->where('jenis_dokumen', $request->jenis);
        }

        // Status
        if ($request->filled('status')) {

            $today = Carbon::today();

            if ($request->status == 'expired') {
                $query->whereDate('tanggal_expired', '<', $today);
            }

            if ($request->status == 'warning') {
                $query->whereBetween('tanggal_expired', [
                    $today,
                    $today->copy()->addDays(30)
                ]);
            }

            if ($request->status == 'aktif') {
                $query->whereDate('tanggal_expired', '>', $today->copy()->addDays(30));
            }

        }

        $dokumens = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dokumen.index', compact('dokumens'));
    }

    /**
     * Form tambah dokumen.
     */
    public function create(Request $request)
    {
        $kendaraans = Kendaraan::orderBy('nomor_unit')->get();

        $kendaraanTerpilih = $request->kendaraan;

        return view('dokumen.create', compact(
            'kendaraans',
            'kendaraanTerpilih'
        ));
    }

    /**
     * Menyimpan dokumen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',

            'jenis_dokumen' => 'required|in:STNK,KIR,BPKB,Pajak Kendaraan,Surat Jalan,Dokumen Lainnya',

            'nomor_dokumen' => 'required|string|max:100',

            'tanggal_terbit' => 'required|date',

            'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_terbit',

            'tanggal_expired' => 'required|date|after_or_equal:tanggal_berlaku',

            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')
                ->store('dokumen', 'public');
        }

        Dokumen::create($validated);

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Form edit dokumen.
     */
    public function edit(Dokumen $dokumen)
    {
        $kendaraans = Kendaraan::orderBy('nomor_unit')->get();

        return view('dokumen.edit', compact(
            'dokumen',
            'kendaraans'
        ));
    }

    /**
     * Update dokumen.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',

            'jenis_dokumen' => 'required|in:STNK,KIR,BPKB,Pajak Kendaraan,Surat Jalan,Dokumen Lainnya',

            'nomor_dokumen' => 'required|string|max:100',

            'tanggal_terbit' => 'required|date',

            'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_terbit',

            'tanggal_expired' => 'required|date|after_or_equal:tanggal_berlaku',

            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        // Jika upload file baru
        if ($request->hasFile('file')) {

            // Hapus file lama
            if ($dokumen->file) {
                Storage::disk('public')->delete($dokumen->file);
            }

            // Simpan file baru
            $validated['file'] = $request->file('file')
                ->store('dokumen', 'public');
        }

        $dokumen->update($validated);

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diupdate.');
    }

    /**
     * Hapus dokumen.
     */
    public function destroy(Dokumen $dokumen)
    {
        // Hapus file dari storage
        if ($dokumen->file) {
            Storage::disk('public')->delete($dokumen->file);
        }

        // Hapus data database
        $dokumen->delete();

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:dokumens,id',
        ]);

        $dokumens = Dokumen::whereIn('id', $request->ids)->get();

        foreach ($dokumens as $dokumen) {
            if ($dokumen->file) {
                Storage::disk('public')->delete($dokumen->file);
            }

            $dokumen->delete();
        }

        return redirect()
            ->route('dokumen.index')
            ->with('success', count($dokumens) . ' dokumen berhasil dihapus.');
    }
}