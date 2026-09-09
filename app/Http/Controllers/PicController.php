<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * Menampilkan semua data PIC.
     */
    public function index()
    {
        $pics = Pic::withCount('kendaraans')
            ->latest()
            ->paginate(10);

        return view('pic.index', compact('pics'));
    }

    /**
     * Menampilkan form tambah PIC.
     */
    public function create()
    {
        return view('pic.create');
    }

    /**
     * Menyimpan PIC baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Pic::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()
            ->route('pic.index')
            ->with('success', 'Data PIC berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail PIC.
     */
    public function show(Pic $pic)
    {
        $pic->load('kendaraans');

        return view('pic.show', compact('pic'));
    }

    /**
     * Menampilkan form edit PIC.
     */
    public function edit(Pic $pic)
    {
        return view('pic.edit', compact('pic'));
    }

    /**
     * Mengupdate PIC.
     */
    public function update(Request $request, Pic $pic)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $pic->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()
            ->route('pic.index')
            ->with('success', 'Data PIC berhasil diupdate.');
    }

    /**
     * Menghapus PIC.
     */
    public function destroy(Pic $pic)
    {
        if ($pic->kendaraans()->exists()) {

            return redirect()
                ->route('pic.index')
                ->with(
                    'error',
                    'PIC tidak dapat dihapus karena masih menangani kendaraan.'
                );
        }

        $pic->delete();

        return redirect()
            ->route('pic.index')
            ->with('success', 'Data PIC berhasil dihapus.');
    }
}