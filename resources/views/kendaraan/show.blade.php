@extends('adminlte::page')

@section('title', 'Detail Unit')

@section('css')

<link rel="stylesheet" href="{{ asset('css/kendaraan.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    
    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-car-side"></i>
        </div>

        <div>
            <h1>Detail Unit</h1>
            <p>Informasi lengkap kendaraan dan dokumen GP Fleet Management System.</p>
        </div>

    </div>

    <div class="gp-page-actions">

        <a href="{{ route('kendaraan.index') }}" class="gp-btn gp-btn-light">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

        <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="gp-btn gp-btn-primary">
            <i class="fas fa-pen"></i>
            Edit Unit
        </a>

    </div>
    

</div>

@stop

@section('content')

<div class="gp-detail-card">

    
    {{-- FOTO + IDENTITAS --}}
    <div class="gp-detail-top">

        <div class="gp-detail-photo">

            @if($kendaraan->foto)

                <img src="{{ asset('storage/' . $kendaraan->foto) }}" alt="Foto Unit">

            @else

                <div class="gp-detail-no-photo">

                    <i class="fas fa-car-side"></i>

                    <span>Belum Ada Foto</span>

                </div>

            @endif

        </div>

        <div class="gp-detail-info">

            <div class="gp-detail-badge">

                @if($kendaraan->kategori == 'Kendaraan')
                    <span class="gp-badge gp-badge-green">
                        <i class="fas fa-circle"></i>
                        Kendaraan
                    </span>

                @elseif($kendaraan->kategori == 'HV')

                    <span class="gp-badge gp-badge-blue">
                        <i class="fas fa-circle"></i>
                        Heavy Vehicle
                    </span>

                @else

                    <span class="gp-badge gp-badge-gray">
                        <i class="fas fa-circle"></i>
                        Heavy Equipment
                    </span>

                @endif

            </div>

            <h2>{{ $kendaraan->nomor_unit }}</h2>

            <p>{{ $kendaraan->merk }} • {{ $kendaraan->tipe }}</p>

            <div class="gp-detail-status">

                @if($kendaraan->status == "Aktif")

                    <span class="gp-status active">
                        <span class="gp-status-dot"></span>
                        Aktif
                    </span>

                @else

                    <span class="gp-status inactive">
                        <span class="gp-status-dot"></span>
                        Tidak Aktif
                    </span>

                @endif

            </div>

        </div>

    </div>

    {{-- GRID INFORMASI --}}
    <div class="gp-detail-grid">

        <div class="gp-info-item">
            <span>Plat Nomor</span>
            <strong>{{ $kendaraan->plat_nomor ?: '-' }}</strong>
        </div>

        <div class="gp-info-item">
            <span>Nomor Unit</span>
            <strong>{{ $kendaraan->nomor_unit }}</strong>
        </div>

        <div class="gp-info-item">
            <span>Merk Unit</span>
            <strong>{{ $kendaraan->merk }}</strong>
        </div>

        <div class="gp-info-item">
            <span>Tahun Kendaraan</span>
            <strong>{{ $kendaraan->tahun }}</strong>
        </div>

        <div class="gp-info-item">
            <span>Project</span>
            <strong>{{ $kendaraan->project ?: '-' }}</strong>
        </div>

        <div class="gp-info-item">
            <span>Kategori</span>
            <strong>{{ $kendaraan->kategori }}</strong>
        </div>

    </div>
    

</div>

{{-- PIC --}}

<div class="gp-detail-card mt-4">

    
    <div class="gp-section-heading">
        <i class="fas fa-user-tie"></i>
        Informasi PIC
    </div>

    @if($kendaraan->pic)

        <div class="gp-pic-card">

            <div class="gp-pic-avatar-lg">

                {{ strtoupper(substr($kendaraan->pic->nama, 0, 1)) }}

            </div>

            <div class="gp-pic-detail">

                <h4>{{ $kendaraan->pic->nama }}</h4>

                <p>{{ $kendaraan->pic->jabatan ?: 'Belum ada jabatan' }}</p>

                <div class="gp-pic-contact">

                    <div>
                        <i class="fas fa-phone-alt"></i>
                        {{ $kendaraan->pic->no_hp ?: '-' }}
                    </div>

                    <div>
                        <i class="fas fa-envelope"></i>
                        {{ $kendaraan->pic->email ?: '-' }}
                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="gp-empty-state">

            <div class="gp-empty-icon">
                <i class="fas fa-user-slash"></i>
            </div>

            <strong>Belum Ada PIC</strong>

            <span>Unit ini belum memiliki penanggung jawab.</span>

        </div>

    @endif
    

</div>

{{-- DOKUMEN --}}

<div class="gp-detail-card mt-4">

    
    <div class="gp-table-header">

        <div class="gp-table-title">

            <div class="gp-table-title-icon">
                <i class="fas fa-file-alt"></i>
            </div>

            <div>

                <h5>Dokumen Kendaraan</h5>

                <span>Dokumen yang terhubung dengan unit ini.</span>

            </div>

        </div>

        <a href="{{ route('dokumen.create', ['kendaraan' => $kendaraan->id]) }}" class="gp-btn gp-btn-primary">
        
            <i class="fas fa-plus"></i>
        
            Tambah Dokumen
        
        </a>

        <div class="gp-data-count">
            <strong>{{ $kendaraan->dokumens->count() }}</strong>
            <span>Dokumen</span>
        </div>

    </div>


    @if($kendaraan->dokumens->count())

        <div class="gp-table-wrapper">

            <table class="gp-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Jenis Dokumen</th>

                        <th>Nomor Dokumen</th>

                        <th>Tanggal Terbit</th>

                        <th>Tanggal Expired</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($kendaraan->dokumens as $dokumen)

                            @php

        $expired = $dokumen->tanggal_expired
            ? \Carbon\Carbon::parse($dokumen->tanggal_expired)
            : null;

                            @endphp

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $dokumen->jenis_dokumen }}</strong>
                                </td>

                                <td>{{ $dokumen->nomor_dokumen ?: '-' }}</td>

                                <td>
                                    {{ $dokumen->tanggal_terbit
            ? \Carbon\Carbon::parse($dokumen->tanggal_terbit)->format('d M Y')
            : '-' }}
                                </td>

                                <td>
                                    {{ $expired ? $expired->format('d M Y') : '-' }}
                                </td>

                                <td>

                                    @if(!$expired)

                                        <span class="gp-status inactive">
                                            <span class="gp-status-dot"></span>
                                            Tidak Ada Tanggal
                                        </span>

                                    @elseif($expired->isPast())

                                        <span class="gp-status inactive">
                                            <span class="gp-status-dot"></span>
                                            Expired
                                        </span>

                                    @elseif($expired->diffInDays(now()) <= 30)

                                        <span class="gp-status warning">
                                            <span class="gp-status-dot"></span>
                                            Segera Expired
                                        </span>

                                    @else

                                        <span class="gp-status active">
                                            <span class="gp-status-dot"></span>
                                            Aktif
                                        </span>

                                    @endif

                                </td>

                            </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="gp-empty-state">

            <div class="gp-empty-icon">
                <i class="fas fa-file-circle-xmark"></i>
            </div>

            <strong>Belum Ada Dokumen</strong>

            <span>Unit ini belum memiliki dokumen yang terdaftar.</span>

        </div>

    @endif
    

</div>

{{-- INFORMASI SISTEM --}}

<div class="gp-detail-card mt-4">

    
    <div class="gp-section-heading">
        <i class="fas fa-history"></i>
        Informasi Sistem
    </div>

    <div class="gp-detail-grid">

        <div class="gp-info-item">
            <span>Dibuat Pada</span>
            <strong>
                {{ $kendaraan->created_at
    ? $kendaraan->created_at->format('d F Y • H:i')
    : '-' }}
            </strong>
        </div>

        <div class="gp-info-item">
            <span>Terakhir Diupdate</span>
            <strong>
                {{ $kendaraan->updated_at
    ? $kendaraan->updated_at->format('d F Y • H:i')
    : '-' }}
            </strong>
        </div>

    </div>
    

</div>

@stop