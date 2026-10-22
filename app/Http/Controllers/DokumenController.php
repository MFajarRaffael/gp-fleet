<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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


    public function export(Request $request)
    {
        $format = $request->query('format', 'xlsx');
        $selected = $request->boolean('selected');

        // Kompatibilitas dengan tombol print yang lama.
        if ($format === 'print') {
            $format = 'pdf';
        }

        if (!in_array($format, ['xlsx', 'csv', 'pdf'], true)) {
            abort(400, 'Format ekspor tidak valid.');
        }

        $request->validate([
            'ids' => 'required_if:selected,1|array|min:1',
            'ids.*' => 'integer|distinct|exists:dokumens,id',
        ]);

        $query = Dokumen::with('kendaraan');

        if ($selected) {
            $query->whereIn('id', $request->input('ids', []));
        } else {
            // Pencarian.
            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                        ->orWhereHas('kendaraan', function ($kendaraan) use ($search) {
                            $kendaraan->where('nomor_unit', 'like', "%{$search}%")
                                ->orWhere('plat_nomor', 'like', "%{$search}%")
                                ->orWhere('tipe', 'like', "%{$search}%");
                        });
                });
            }

            // Filter jenis dokumen.
            if ($request->filled('jenis')) {
                $query->where('jenis_dokumen', $request->jenis);
            }

            // Filter status masa berlaku.
            $today = Carbon::today();

            if ($request->status === 'expired') {
                $query->whereDate('tanggal_expired', '<', $today);
            } elseif ($request->status === 'warning') {
                $query->whereBetween('tanggal_expired', [
                    $today,
                    $today->copy()->addDays(30),
                ]);
            } elseif ($request->status === 'aktif') {
                $query->whereDate(
                    'tanggal_expired',
                    '>',
                    $today->copy()->addDays(30)
                );
            }
        }

        $dokumens = $query->latest()->get();

        $headers = [
            'Nomor Unit',
            'Kategori',
            'Plat Nomor',
            'Merk',
            'Tipe',
            'Jenis Dokumen',
            'Nomor Dokumen',
            'Tanggal Terbit',
            'Tanggal Berlaku',
            'Tanggal Expired',
            'Status',
        ];

        $data = $dokumens->map(function ($dokumen) {
            $kendaraan = $dokumen->kendaraan;
            $expired = Carbon::parse($dokumen->tanggal_expired)->startOfDay();
            $today = Carbon::today();

            $status = $expired->lt($today)
                ? 'Expired'
                : ($expired->lte($today->copy()->addDays(30))
                    ? 'Segera Expired'
                    : 'Aktif');

            return [
                $kendaraan?->nomor_unit ?? '-',
                $kendaraan?->kategori ?? '-',
                $kendaraan?->plat_nomor ?? '-',
                $kendaraan?->merk ?? '-',
                $kendaraan?->tipe ?? '-',
                $dokumen->jenis_dokumen ?? '-',
                $dokumen->nomor_dokumen ?? '-',
                $dokumen->tanggal_terbit
                ? Carbon::parse($dokumen->tanggal_terbit)->format('d-m-Y')
                : '-',
                $dokumen->tanggal_berlaku
                ? Carbon::parse($dokumen->tanggal_berlaku)->format('d-m-Y')
                : '-',
                $expired->format('d-m-Y'),
                $status,
            ];
        });

        $filename = 'data-dokumen-' . now()->format('Ymd-His');

        // Export CSV.
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data, $headers) {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $headers, ';');

                foreach ($data as $row) {
                    fputcsv($handle, $row, ';');
                }

                fclose($handle);
            }, $filename . '.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        // Export PDF langsung.
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.dokumen', [
                'headers' => $headers,
                'data' => $data,
                'judul' => 'Data Dokumen Kendaraan',
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        // Export Excel.
        return Excel::download(
            new class ($data, $headers) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            private $headers;

            public function __construct($data, $headers)
            {
                $this->data = $data;
                $this->headers = $headers;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->headers;
            }
            },
            $filename . '.xlsx'
        );
    }
}