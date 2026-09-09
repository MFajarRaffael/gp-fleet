@extends('adminlte::page')

@section('title', 'Detail PIC')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pic.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-user-tie"></i>
        </div>

        <div>
            <h1>Detail Person In Charge</h1>
            <p>Informasi lengkap PIC beserta seluruh kendaraan yang menjadi tanggung jawabnya.</p>
        </div>

    </div>

    <div class="gp-header-actions">

        <a href="{{ route('pic.edit', $pic->id) }}" class="gp-btn gp-btn-primary">
            <i class="fas fa-pen-to-square"></i>
            Edit PIC
        </a>

        <a href="{{ route('pic.index') }}" class="gp-btn gp-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>

</div>

@stop


@section('content')

@php
$jumlahUnit = $pic->kendaraans->count();
$unitAktif = $pic->kendaraans->where('status', 'Aktif')->count();
$unitTidakAktif = $jumlahUnit - $unitAktif;
@endphp


{{-- ============================= --}}
{{-- SUMMARY CARD --}}
{{-- ============================= --}}

<div class="row mb-4">

    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card green">

            <div>
                <span>Total Unit</span>
                <h2>{{ $jumlahUnit }}</h2>
                <small>Kendaraan yang ditangani</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-car"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card success">

            <div>
                <span>Unit Aktif</span>
                <h2>{{ $unitAktif }}</h2>
                <small>Masih beroperasi</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card warning">

            <div>
                <span>Unit Tidak Aktif</span>
                <h2>{{ $unitTidakAktif }}</h2>
                <small>Tidak beroperasi</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-times-circle"></i>
            </div>

        </div>

    </div>

</div>


<div class="row">

    {{-- ============================= --}}
    {{-- PROFILE CARD --}}
    {{-- ============================= --}}

    <div class="col-lg-4 mb-4">

        <div class="gp-profile-card">

            <div class="gp-profile-top">

                <div class="gp-profile-avatar">
                    {{ strtoupper(substr($pic->nama, 0, 1)) }}
                </div>

                <h3>{{ $pic->nama }}</h3>

                <span class="gp-role-badge">
                    {{ $pic->jabatan ?: 'Belum Ada Jabatan' }}
                </span>

            </div>


            <div class="gp-profile-info">

                <div class="gp-info-item">

                    <div class="gp-info-item-icon">
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div>
                        <small>Email</small>
                        <strong>{{ $pic->email ?: '-' }}</strong>
                    </div>

                </div>


                <div class="gp-info-item">

                    <div class="gp-info-item-icon">
                        <i class="fas fa-phone"></i>
                    </div>

                    <div>
                        <small>No. Handphone</small>
                        <strong>{{ $pic->no_hp ?: '-' }}</strong>
                    </div>

                </div>


                <div class="gp-info-item">

                    <div class="gp-info-item-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>

                    <div>
                        <small>Jabatan</small>
                        <strong>{{ $pic->jabatan ?: '-' }}</strong>
                    </div>

                </div>


                <div class="gp-info-item">

                    <div class="gp-info-item-icon">
                        <i class="fas fa-car-side"></i>
                    </div>

                    <div>
                        <small>Total Kendaraan</small>
                        <strong>{{ $jumlahUnit }} Unit</strong>
                    </div>

                </div>

            </div>


            <!-- <div class="gp-profile-footer">

                <a href="{{ route('pic.edit',$pic->id) }}" class="gp-btn gp-btn-primary w-100">

                    <i class="fas fa-pen-to-square"></i>

                    Edit Data PIC

                </a>

            </div> -->

        </div>

    </div>


    {{-- ============================= --}}
    {{-- TABLE UNIT --}}
    {{-- ============================= --}}

    <div class="col-lg-8">

        <div class="gp-table-card">

            <div class="gp-table-header">

                <div class="gp-table-title">

                    <div class="gp-table-title-icon">
                        <i class="fas fa-car"></i>
                    </div>

                    <div>
                        <h5>Unit yang Ditangani</h5>
                        <span>Daftar kendaraan yang menjadi tanggung jawab PIC ini.</span>
                    </div>

                </div>

                <div class="gp-data-count">

                    <strong>{{ $jumlahUnit }}</strong>

                    <span>Unit</span>

                </div>

            </div>


            <div class="gp-table-wrapper">

                @if($jumlahUnit > 0)

                <table class="gp-table">

                    <thead>

                        <tr>

                            <th width="60">No</th>

                            <th>Nomor Unit</th>

                            <th>Plat Nomor</th>

                            <th>Tipe</th>

                            <th>Status</th>

                            <th width="110">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($pic->kendaraans as $kendaraan)

                        <tr>

                            <td class="gp-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <div class="gp-unit">

                                    <div class="gp-unit-avatar">
                                        <i class="fas fa-car-side"></i>
                                    </div>

                                    <div>
                                        <strong>{{ $kendaraan->nomor_unit }}</strong>

                                        <small>{{ $kendaraan->merk }} • {{ $kendaraan->kategori }}</small>
                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="gp-plate">
                                    {{ $kendaraan->plat_nomor ?: '-' }}
                                </span>

                            </td>

                            <td>{{ $kendaraan->tipe }}</td>

                            <td>

                                @if($kendaraan->status == 'Aktif')

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

                            </td>

                            <td>

                                <a href="{{ route('kendaraan.show', $kendaraan->id) }}"
                                   class="gp-action-btn info"
                                   title="Lihat Kendaraan">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                @else

                <div class="gp-empty-state">

                    <div class="gp-empty-icon">
                        <i class="fas fa-car-side"></i>
                    </div>

                    <strong>Belum Ada Kendaraan</strong>

                    <span>PIC ini belum ditugaskan ke kendaraan mana pun.</span>

                </div>

                @endif

            </div>

        </div>

    </div>

</div>

@stop