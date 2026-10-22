<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\KendaraanImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Search berdasarkan plat nomor, nomor unit, merk, tipe, atau project
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', '%' . $search . '%')
                ->orWhere('nomor_unit', 'like', '%' . $search . '%')
                ->orWhere('merk', 'like', '%' . $search . '%')
                ->orWhere('tipe', 'like', '%' . $search . '%')
                ->orWhere('project', 'like', '%' . $search . '%');
            });
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

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:kendaraans,id',
        ]);

        $kendaraans = Kendaraan::whereIn('id', $request->ids)->get();

        foreach ($kendaraans as $kendaraan) {
            if ($kendaraan->foto) {
                Storage::disk('public')->delete($kendaraan->foto);
            }

            $kendaraan->delete();
        }

        return redirect()
            ->route('kendaraan.index')
            ->with('success', count($kendaraans) . ' data unit berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $format = $request->query('format', 'xlsx');
        $scope = $request->query('scope', 'filter');

        // Format print lama tetap didukung sebagai PDF langsung.
        if ($format === 'print') {
            $format = 'pdf';
        }

        if (!in_array($format, ['xlsx', 'csv', 'pdf'], true)) {
            abort(400, 'Format ekspor tidak valid.');
        }

        if (!in_array($scope, ['all', 'filter', 'selected'], true)) {
            abort(400, 'Pilihan ekspor tidak valid.');
        }

        $query = Kendaraan::with('pic');

        if ($scope === 'selected') {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|distinct|exists:kendaraans,id',
            ]);

            $query->whereIn('id', $request->query('ids', []));
        } elseif ($scope === 'filter') {
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->query('kategori'));
            }

            if ($request->filled('search')) {
                $search = $request->query('search');

                $query->where(function ($q) use ($search) {
                    $q->where('plat_nomor', 'like', "%{$search}%")
                        ->orWhere('nomor_unit', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%")
                        ->orWhere('project', 'like', "%{$search}%");
                });
            }
        }

        $kendaraans = $query->orderBy('nomor_unit')->get();

        $headers = [
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

        $data = $kendaraans->map(function ($item) {
            return [
                'Nomor Unit' => $item->nomor_unit,
                'Kategori' => $item->kategori,
                'Plat Nomor' => $item->plat_nomor,
                'Merk' => $item->merk,
                'Tipe' => $item->tipe,
                'Tahun' => $item->tahun,
                'Project' => $item->project,
                'PIC' => $item->pic?->nama ?? '-',
                'Status' => $item->status,
            ];
        });

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.kendaraan', [
                'headers' => $headers,
                'data' => $data,
                'judul' => 'Data Unit Kendaraan',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('data-kendaraan.pdf');
        }

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data, $headers) {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers, ';');

                foreach ($data as $row) {
                    fputcsv($handle, array_values($row), ';');
                }

                fclose($handle);
            }, 'data-kendaraan.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        return Excel::download(
            new \App\Exports\KendaraanExport($data),
            'data-kendaraan.xlsx'
        );
    }

    public function exportDetail(Request $request, Kendaraan $kendaraan)
    {
        $format = $request->query('format', 'xlsx');

        if ($format === 'print') {
            $format = 'pdf';
        }

        if (!in_array($format, ['xlsx', 'csv', 'pdf'], true)) {
            abort(400, 'Format ekspor tidak valid.');
        }

        $kendaraan->load(['pic', 'dokumens']);

        $headers = [
            'Nomor Unit',
            'Kategori',
            'Plat Nomor',
            'Merk',
            'Tipe',
            'Tahun',
            'Project',
            'PIC',
            'Status',
            'Jenis Dokumen',
            'Nomor Dokumen',
            'Tanggal Terbit',
            'Tanggal Berlaku',
            'Tanggal Expired',
        ];

        $dokumens = $kendaraan->dokumens;

        if ($dokumens->isEmpty()) {
            $dokumens = collect([null]);
        }

        $data = $dokumens->map(function ($dokumen) use ($kendaraan) {
            return [
                'Nomor Unit' => $kendaraan->nomor_unit,
                'Kategori' => $kendaraan->kategori,
                'Plat Nomor' => $kendaraan->plat_nomor ?: '-',
                'Merk' => $kendaraan->merk,
                'Tipe' => $kendaraan->tipe,
                'Tahun' => $kendaraan->tahun,
                'Project' => $kendaraan->project ?: '-',
                'PIC' => $kendaraan->pic?->nama ?? '-',
                'Status' => $kendaraan->status,
                'Jenis Dokumen' => $dokumen?->jenis_dokumen ?? '-',
                'Nomor Dokumen' => $dokumen?->nomor_dokumen ?? '-',
                'Tanggal Terbit' => $dokumen?->tanggal_terbit
                    ? \Carbon\Carbon::parse($dokumen->tanggal_terbit)->format('d-m-Y')
                    : '-',
                'Tanggal Berlaku' => $dokumen?->tanggal_berlaku
                    ? \Carbon\Carbon::parse($dokumen->tanggal_berlaku)->format('d-m-Y')
                    : '-',
                'Tanggal Expired' => $dokumen?->tanggal_expired
                    ? \Carbon\Carbon::parse($dokumen->tanggal_expired)->format('d-m-Y')
                    : '-',
            ];
        });

        $filename = 'detail-unit-' . $kendaraan->nomor_unit;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.kendaraan', [
                'headers' => $headers,
                'data' => $data,
                'judul' => 'Detail Unit: ' . $kendaraan->nomor_unit,
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data, $headers) {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers, ';');

                foreach ($data as $row) {
                    fputcsv($handle, array_values($row), ';');
                }

                fclose($handle);
            }, $filename . '.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        return Excel::download(
            new class ($data, $headers) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            protected $headers;

            public function __construct($data, $headers)
            {
                $this->data = $data;
                $this->headers = $headers;
            }

            public function collection()
            {
                return $this->data->values();
            }

            public function headings(): array
            {
                return $this->headers;
            }
            },
            $filename . '.xlsx'
        );
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