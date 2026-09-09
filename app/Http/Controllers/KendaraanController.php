<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\KendaraanImport;
use Maatwebsite\Excel\Facades\Excel;

class KendaraanController extends Controller
{
    /**
     * Menampilkan semua data kendaraan.
     */
    public function index(Request $request)
    {
        $query = Kendaraan::with('pic');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $kendaraans = $query
            ->orderBy('nomor_unit')
            ->paginate(10)
            ->withQueryString();

        return view('kendaraan.index', compact('kendaraans'));
    }

    /**
     * Menampilkan form tambah kendaraan.
     */
    public function create()
    {
        $pics = Pic::orderBy('nama')->get();

        return view('kendaraan.create', compact('pics'));
    }

    /**
     * Menyimpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'kategori' => 'required|in:Kendaraan,HV,HE',

            'tipe' => 'required|string|max:100',

            // Plat boleh kosong
            'plat_nomor' => 'nullable|string|max:50|unique:kendaraans,plat_nomor',

            'nomor_unit' => 'required|string|max:50|unique:kendaraans,nomor_unit',

            'pic_id' => 'nullable|exists:pics,id',

            'merk' => 'required|string|max:100',

            'tahun' => 'nullable|integer|min:1900|max:2100',

            // Project boleh kosong
            'project' => 'nullable|string|max:255',

            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('kendaraan', 'public');
        }

        Kendaraan::create($validated);

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Data Unit berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kendaraan.
     */
    public function show(Kendaraan $kendaraan)
    {
        $kendaraan->load([
            'pic',
            'dokumens',
        ]);

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Menampilkan form edit kendaraan.
     */
    public function edit(Kendaraan $kendaraan)
    {
        $pics = Pic::orderBy('nama')->get();

        return view('kendaraan.edit', compact(
            'kendaraan',
            'pics'
        ));
    }

    /**
     * Mengupdate kendaraan.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'kategori' => 'required|in:Kendaraan,HV,HE',

            'tipe' => 'required|string|max:100',

            // Plat boleh kosong
            'plat_nomor' => 'nullable|string|max:50|unique:kendaraans,plat_nomor,' . $kendaraan->id,

            'nomor_unit' => 'required|string|max:50|unique:kendaraans,nomor_unit,' . $kendaraan->id,

            'pic_id' => 'nullable|exists:pics,id',

            'merk' => 'required|string|max:100',

            'tahun' => 'required|integer|min:1900|max:2100',

            // Project boleh kosong
            'project' => 'nullable|string|max:255',

            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($kendaraan->foto) {
                Storage::disk('public')->delete($kendaraan->foto);
            }

            // Simpan foto baru
            $validated['foto'] = $request->file('foto')
                ->store('kendaraan', 'public');
        }

        $kendaraan->update($validated);

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Data Unit berhasil diupdate.');
    }

    /**
     * Menghapus kendaraan.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        // Hapus foto kendaraan
        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        $kendaraan->delete();

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Data Unit berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new KendaraanImport, $request->file('file'));

            return redirect()
                ->route('kendaraan.index')
                ->with('success', 'Data Unit berhasil diimport dari Excel.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}