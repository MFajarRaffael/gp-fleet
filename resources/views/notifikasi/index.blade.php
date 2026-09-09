@extends('adminlte::page')

@section('title', 'Riwayat Notifikasi')

@section('css')
<link rel="stylesheet" href="{{ asset('css/notifikasi.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-bell"></i>
        </div>

        <div>
            <h1>Riwayat Notifikasi Dokumen</h1>
            <p>Riwayat seluruh email notifikasi dokumen kendaraan PT Green Planet Indonesia.</p>
        </div>

    </div>

</div>

@stop


@section('content')

@if(session('success'))

    <div class="gp-alert gp-alert-success">

        <div class="gp-alert-icon">
            <i class="fas fa-circle-check"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Berhasil</strong>
            <span>{{ session('success') }}</span>
        </div>

    </div>

@endif

{{-- SUMMARY --}}
<div class="row mb-4">

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="gp-summary-card green">

            <div>
                <span>Total Notifikasi</span>
                <h2>{{ $summary['total'] }}</h2>
                <small>Semua histori email.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-envelope"></i>
            </div>

        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="gp-summary-card blue">

            <div>
                <span>Hari Ini</span>
                <h2>{{ $summary['hari_ini'] }}</h2>
                <small>Email terkirim hari ini.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-calendar-day"></i>
            </div>

        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="gp-summary-card warning">

            <div>
                <span>Akan Expired</span>
                <h2>{{ $summary['warning'] }}</h2>
                <small>H-30 sampai H-1.</small>
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
                <h2>{{ $summary['expired'] }}</h2>
                <small>Notifikasi H-0.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

        </div>
    </div>

</div>


{{-- FILTER --}}
<div class="gp-filter-card">

    <div class="gp-filter-header">

        <div class="gp-filter-title">

            <div class="gp-filter-icon">
                <i class="fas fa-filter"></i>
            </div>

            <div>
                <h5>Filter Riwayat</h5>
                <span>Cari histori notifikasi berdasarkan status, jenis, tanggal, atau unit.</span>
            </div>

        </div>

    </div>

    <form method="GET">

        <div class="row">

            <div class="col-lg-3">
                <div class="form-group">
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="">Semua Status</option>

                        <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>
                            Akan Expired
                        </option>

                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                            Sudah Expired
                        </option>

                    </select>

                </div>
            </div>

            <div class="col-lg-3">

                <div class="form-group">

                    <label>Jenis Notifikasi</label>

                    <select name="jenis" class="form-control">

                        <option value="">Semua</option>

                        @foreach(['H-30', 'H-15', 'H-5', 'H-4', 'H-3', 'H-2', 'H-1', 'H-0'] as $jenis)

                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="col-lg-3">

                <div class="form-group">

                    <label>Tanggal Kirim</label>

                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">

                </div>

            </div>

            <div class="col-lg-3">

                <div class="form-group">

                    <label>Pencarian</label>

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Nomor Unit / Plat / PIC">

                </div>

            </div>

        </div>

        <div class="gp-filter-footer">

            <button class="gp-btn gp-btn-primary">

                <i class="fas fa-search"></i>
                Terapkan Filter

            </button>

            <a href="{{ route('notifikasi.index') }}" class="gp-btn gp-btn-outline">

                <i class="fas fa-rotate-left"></i>
                Reset

            </a>

        </div>

    </form>

</div>


{{-- TABLE --}}
<div class="gp-table-card">

    <div class="gp-table-header">

        <div class="gp-table-title">

            <div class="gp-table-title-icon">
                <i class="fas fa-history"></i>
            </div>

            <div>
                <h5>Riwayat Pengiriman Email</h5>
                <span>Histori email notifikasi dokumen kendaraan.</span>
            </div>

        </div>

        <div class="gp-data-count">
            <strong>{{ $notifikasis->total() }}</strong>
            <span>Riwayat</span>
        </div>

    </div>


    <div class="gp-table-wrapper">

        <table class="gp-table">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Unit</th>
                    <th>PIC</th>
                    <th>Dokumen</th>
                    <th>Tanggal Expired</th>
                    <th>Notifikasi</th>
                    <th>Status</th>
                    <th>Tanggal Kirim</th>

                </tr>

            </thead>

            <tbody>

                @forelse($notifikasis as $item)

                    <tr>

                        <td>{{ $notifikasis->firstItem() + $loop->index }}</td>

                        <td>

                            <div class="gp-unit">

                                <div class="gp-unit-avatar">
                                    <i class="fas fa-car-side"></i>
                                </div>

                                <div>
                                    <strong>{{ $item->kendaraan->nomor_unit ?? '-' }}</strong>
                                    <small>{{ $item->kendaraan->plat_nomor ?? '-' }}</small>
                                </div>

                            </div>

                        </td>

                        <td>

                            <strong>{{ $item->pic->nama ?? '-' }}</strong>

                            <small>{{ $item->pic->email ?? '-' }}</small>

                        </td>

                        <td>

                            <strong>{{ $item->dokumen->jenis_dokumen ?? '-' }}</strong>

                            <small>{{ $item->dokumen->nomor_dokumen ?? '-' }}</small>

                        </td>

                        <td>

                            {{ optional($item->dokumen->tanggal_expired)->format('d M Y') }}

                        </td>

                        <td>

                            @php

                                $badgeClass = match ($item->jenis_notifikasi) {

                                    'H-30' => 'green',
                                    'H-15' => 'blue',
                                    'H-5' => 'yellow',
                                    'H-4', 'H-3', 'H-2', 'H-1' => 'orange',
                                    'H-0' => 'red',
                                    default => 'gray'

                                };

                            @endphp

                            <span class="gp-badge {{ $badgeClass }}">
                                {{ $item->jenis_notifikasi }}
                            </span>

                        </td>

                        <td>

                            @if($item->status == 'Berhasil')

                                <span class="gp-status success">

                                    <span class="gp-status-dot"></span>

                                    Berhasil

                                </span>

                            @else

                                <span class="gp-status danger">

                                    <span class="gp-status-dot"></span>

                                    Gagal

                                </span>

                            @endif

                        </td>

                        <td>

                            <strong>{{ $item->tanggal_kirim->format('d M Y') }}</strong>

                            <small>{{ $item->tanggal_kirim->format('H:i') }} WIB</small>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <div class="gp-empty-state">

                                <div class="gp-empty-icon">
                                    <i class="fas fa-bell-slash"></i>
                                </div>

                                <strong>Belum Ada Riwayat Notifikasi</strong>

                                <span>Email notifikasi akan muncul di sini setelah scheduler mengirim email.</span>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="gp-table-footer">

        <div class="gp-pagination-info">

            Menampilkan

            <strong>{{ $notifikasis->firstItem() ?? 0 }}</strong>

            -

            <strong>{{ $notifikasis->lastItem() ?? 0 }}</strong>

            dari

            <strong>{{ $notifikasis->total() }}</strong>

            data.

        </div>

        <div class="gp-pagination">

            {{ $notifikasis->onEachSide(1)->links('pagination::bootstrap-4') }}

        </div>

    </div>

</div>

@stop