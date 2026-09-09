@extends('adminlte::page')

@section('title', 'Manajemen Dokumen')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dokumen.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-file-alt"></i>
        </div>

        <div>
            <h1>Manajemen Dokumen</h1>
            <p>Kelola seluruh dokumen kendaraan, heavy vehicle, dan heavy equipment PT Green Planet Indonesia.</p>
        </div>

    </div>

    <a href="{{ route('dokumen.create') }}" class="gp-btn gp-btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Dokumen
    </a>

</div>

@stop


@section('content')

{{-- ============================= --}}
{{-- SUCCESS ALERT --}}
{{-- ============================= --}}

@if(session('success'))

    <div class="gp-alert gp-alert-success">

        <div class="gp-alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Berhasil</strong>
            <span>{{ session('success') }}</span>
        </div>

        <button class="close gp-alert-close" data-dismiss="alert">&times;</button>

    </div>

@endif


{{-- ============================= --}}
{{-- SUMMARY CARD --}}
{{-- ============================= --}}

@php

$totalDokumen = $dokumens->total();

$aktif = 0;
$warning = 0;
$expired = 0;

foreach ($dokumens as $item) {

    $hari = now()->startOfDay()->diffInDays(
        \Carbon\Carbon::parse($item->tanggal_expired)->startOfDay(),
        false
    );

    if ($hari < 0) {
        $expired++;
    } elseif ($hari <= 30) {
        $warning++;
    } else {
        $aktif++;
    }

}

@endphp


<div class="row mb-4">

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card green">

            <div>

                <span>Total Dokumen</span>

                <h2>{{ $totalDokumen }}</h2>

                <small>Semua dokumen unit</small>

            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-folder-open"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card success">

            <div>

                <span>Dokumen Aktif</span>

                <h2>{{ $aktif }}</h2>

                <small>Masa berlaku masih aman</small>

            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card warning">

            <div>

                <span>Segera Expired</span>

                <h2>{{ $warning }}</h2>

                <small>30 hari menuju expired</small>

            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-bell"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card danger">

            <div>

                <span>Sudah Expired</span>

                <h2>{{ $expired }}</h2>

                <small>Perlu diperpanjang</small>

            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

        </div>

    </div>

</div>

{{-- ============================= --}}
{{-- FILTER DOKUMEN --}}
{{-- ============================= --}}

<div class="gp-filter-card">

    <form method="GET" action="{{ route('dokumen.index') }}">

        <div class="row align-items-end">

            {{-- Search --}}
            <div class="col-lg-4 col-md-6">

                <label>Pencarian</label>

                <div class="gp-search-box">

                    <i class="fas fa-search"></i>

                    <input type="text" name="search" class="form-control"
                        placeholder="Nomor unit / plat / nomor dokumen" value="{{ request('search') }}">

                </div>

            </div>

            {{-- Status --}}
            <div class="col-lg-3 col-md-6">

                <label>Status Dokumen</label>

                <select name="status" class="form-control">

                    <option value="">Semua Status</option>

                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>
                        Dokumen Aktif
                    </option>

                    <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>
                        Segera Expired
                    </option>

                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                        Sudah Expired
                    </option>

                </select>

            </div>

            {{-- Jenis --}}
            <div class="col-lg-3 col-md-6">

                <label>Jenis Dokumen</label>

                <select name="jenis" class="form-control">

                    <option value="">Semua Jenis</option>

                    @foreach([
                            'STNK',
                            'KIR',
                            'BPKB',
                            'Pajak Kendaraan',
                            'Surat Jalan',
                            'Dokumen Lainnya'
                        ] as $jenis)

                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>

                            {{ $jenis }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Button --}}
            <div class="col-lg-2 col-md-6">

                <div class="gp-filter-buttons">

                    <button class="gp-btn gp-btn-primary w-100">

                        <i class="fas fa-filter"></i>

                        Filter

                    </button>

                    <a href="{{ route('dokumen.index') }}" class="gp-btn gp-btn-outline w-100 mt-2">

                        Reset

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

{{-- ============================= --}}
{{-- TABLE CARD --}}
{{-- ============================= --}}

<div class="gp-table-card">

    <div class="gp-table-header">

        <div class="gp-table-title">

            <div class="gp-table-title-icon">
                <i class="fas fa-file-alt"></i>
            </div>

            <div>
                <h5>Data Dokumen Unit</h5>
                <span>Daftar seluruh dokumen kendaraan GP Fleet.</span>
            </div>

        </div>

        <div class="gp-data-count">
            <strong>{{ $dokumens->total() }}</strong>
            <span>Dokumen</span>
        </div>

    </div>


    <div class="gp-table-wrapper">

        <table class="gp-table">

            <thead>

                <tr>

                    <th width="60">No</th>

                    <th width="260">Unit</th>

                    <th width="120">Kategori</th>

                    <th width="180">Jenis Dokumen</th>

                    <th width="170">Nomor Dokumen</th>

                    <th width="150">Expired</th>

                    <th width="170">Status</th>

                    <th width="110">File</th>

                    <th width="130">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($dokumens as $dokumen)

                @php

    $expiredDate = \Carbon\Carbon::parse($dokumen->tanggal_expired);

    $hariTersisa = now()->startOfDay()->diffInDays(
        $expiredDate->startOfDay(),
        false
    );

                @endphp

                <tr>

                    {{-- NO --}}

                    <td class="gp-number">
                        {{ $loop->iteration }}
                    </td>


                    {{-- UNIT --}}

                    <td>

                        <div class="gp-unit">

                            <div class="gp-unit-avatar">

                                @if($dokumen->kendaraan && $dokumen->kendaraan->foto)

                                    <img src="{{ asset('storage/' . $dokumen->kendaraan->foto) }}">

                                @else

                                    <i class="fas fa-car-side"></i>

                                @endif

                            </div>

                            <div>

                                @if($dokumen->kendaraan)

                                    <strong>{{ $dokumen->kendaraan->nomor_unit }}</strong>

                                    <small>{{ $dokumen->kendaraan->merk }} • {{ $dokumen->kendaraan->tipe }}</small>

                                    @if($dokumen->kendaraan->plat_nomor)

                                        <small class="gp-plate">
                                            {{ $dokumen->kendaraan->plat_nomor }}
                                        </small>

                                    @endif

                                @else

                                    <span class="text-danger">Unit tidak ditemukan</span>

                                @endif

                            </div>

                        </div>

                    </td>


                    {{-- KATEGORI --}}

                    <td>

                        @if($dokumen->kendaraan)

                            @if($dokumen->kendaraan->kategori == "Kendaraan")

                                <span class="gp-badge gp-badge-green">
                                    Kendaraan
                                </span>

                            @elseif($dokumen->kendaraan->kategori == "HV")

                                <span class="gp-badge gp-badge-blue">
                                    Heavy Vehicle
                                </span>

                            @else

                                <span class="gp-badge gp-badge-gray">
                                    Heavy Equipment
                                </span>

                            @endif

                        @else

                            -

                        @endif

                    </td>


                    {{-- JENIS DOKUMEN --}}

                    <td>

                        <strong class="gp-doc-title">
                            {{ $dokumen->jenis_dokumen }}
                        </strong>

                    </td>


                    {{-- NOMOR DOKUMEN --}}

                    <td>

                        <span class="gp-doc-number">
                            {{ $dokumen->nomor_dokumen }}
                        </span>

                    </td>


                    {{-- TANGGAL EXPIRED --}}

                    <td>

                        <div class="gp-date-info">

                            <strong>{{ $expiredDate->format('d M Y') }}</strong>

                            <small>{{ $expiredDate->translatedFormat('l') }}</small>

                        </div>

                    </td>


                    {{-- STATUS --}}

                    <td>

                        @if($hariTersisa < 0)

                            <span class="gp-status expired">

                                <span class="gp-status-dot"></span>

                                Expired

                            </span>

                            <small class="gp-status-text text-danger">

                                Terlambat {{ abs($hariTersisa) }} hari

                            </small>

                        @elseif($hariTersisa <= 30)

                            <span class="gp-status warning">

                                <span class="gp-status-dot"></span>

                                Segera Expired

                            </span>

                            <small class="gp-status-text text-warning">

                                {{ $hariTersisa }} hari lagi

                            </small>

                        @else

                            <span class="gp-status active">

                                <span class="gp-status-dot"></span>

                                Aktif

                            </span>

                            <small class="gp-status-text text-success">

                                {{ $hariTersisa }} hari lagi

                            </small>

                        @endif

                    </td>


                    {{-- FILE --}}

                    <td>

                        @if($dokumen->file)

                            <a href="{{ asset('storage/' . $dokumen->file) }}"
                                target="_blank"
                                class="gp-file-btn">

                                <i class="fas fa-file-download"></i>

                            </a>

                        @else

                            <span class="gp-empty-file">
                                Tidak Ada
                            </span>

                        @endif

                    </td>


                    {{-- AKSI --}}

                    <td>

                        <div class="gp-actions">

                            <a href="{{ route('dokumen.edit', $dokumen->id) }}"
                                class="gp-action-btn edit"
                                title="Edit">

                                <i class="fas fa-pen"></i>

                            </a>

                            <form
                                action="{{ route('dokumen.destroy', $dokumen->id) }}"
                                method="POST"
                                class="gp-delete-form">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin ingin menghapus dokumen ini?')"
                                    class="gp-action-btn delete">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="9">

                        <div class="gp-empty-state">

                            <div class="gp-empty-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>

                            <strong>Belum Ada Dokumen</strong>

                            <span>Silakan tambahkan dokumen kendaraan terlebih dahulu.</span>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    <div class="gp-table-footer">

        <div class="gp-pagination-info">

            Menampilkan

            <strong>{{ $dokumens->firstItem() ?? 0 }}</strong>

            -

            <strong>{{ $dokumens->lastItem() ?? 0 }}</strong>

            dari

            <strong>{{ $dokumens->total() }}</strong>

            data.

        </div>

        <div class="gp-pagination">

            {{ $dokumens->onEachSide(1)->links('pagination::bootstrap-4') }}

        </div>

    </div>

</div>

@stop