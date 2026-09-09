@extends('adminlte::page')

@section('title', 'Data PIC')

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
            <h1>Manajemen PIC</h1>
            <p>Kelola seluruh Person In Charge kendaraan, heavy vehicle, dan heavy equipment PT Green Planet Indonesia.
            </p>
        </div>

    </div>

    <a href="{{ route('pic.create') }}" class="gp-btn gp-btn-primary">
        <i class="fas fa-plus"></i>
        Tambah PIC
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

        <button class="close gp-alert-close" data-dismiss="alert">
            &times;
        </button>

    </div>

@endif


{{-- ERROR --}}

@if(session('error'))

    <div class="gp-alert gp-alert-danger">

        <div class="gp-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Terjadi Kesalahan</strong>
            <span>{{ session('error') }}</span>
        </div>

        <button class="close gp-alert-close" data-dismiss="alert">
            &times;
        </button>

    </div>

@endif


{{-- ============================= --}}
{{-- SUMMARY CARD --}}
{{-- ============================= --}}

@php

    $totalPIC = $pics->total();

    $totalUnit = 0;

    foreach ($pics as $pic) {
        $totalUnit += $pic->kendaraans_count ?? $pic->kendaraans->count();
    }

    $punyaUnit = $pics->filter(function ($pic) {
        return ($pic->kendaraans_count ?? $pic->kendaraans->count()) > 0;
    })->count();

    $belumPunyaUnit = $totalPIC - $punyaUnit;

@endphp


<div class="row mb-4">

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card green">

            <div>
                <span>Total PIC</span>
                <h2>{{ $totalPIC }}</h2>
                <small>Semua person in charge</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-users"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card success">

            <div>
                <span>PIC Memiliki Unit</span>
                <h2>{{ $punyaUnit }}</h2>
                <small>Sudah terhubung kendaraan</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card warning">

            <div>
                <span>Belum Ada Unit</span>
                <h2>{{ $belumPunyaUnit }}</h2>
                <small>PIC belum memiliki kendaraan</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-user-clock"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6 mb-3">

        <div class="gp-summary-card blue">

            <div>
                <span>Total Kendaraan PIC</span>
                <h2>{{ $totalUnit }}</h2>
                <small>Semua kendaraan terhubung</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-car"></i>
            </div>

        </div>

    </div>

</div>


{{-- ============================= --}}
{{-- TABLE --}}
{{-- ============================= --}}

<div class="gp-table-card">

    <div class="gp-table-header">

        <div class="gp-table-title">

            <div class="gp-table-title-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>
                <h5>Data Person In Charge</h5>
                <span>Daftar seluruh PIC kendaraan GP Fleet.</span>
            </div>

        </div>

        <div class="gp-data-count">
            <strong>{{ $pics->total() }}</strong>
            <span>PIC</span>
        </div>

    </div>


    <div class="gp-table-wrapper">

        <table class="gp-table">

            <thead>

                <tr>

                    <th width="60">No</th>

                    <th width="280">PIC</th>

                    <th>Email</th>

                    <th>No HP</th>

                    <th>Jabatan</th>

                    <th width="120">Jumlah Unit</th>

                    <th width="150">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($pics as $pic)

                    @php
                        $jumlahUnit = $pic->kendaraans_count ?? $pic->kendaraans->count();
                    @endphp

                    <tr>

                        {{-- NO --}}
                        <td class="gp-number">
                            {{ $loop->iteration }}
                        </td>

                        {{-- PIC --}}
                        <td>

                            <div class="gp-pic">

                                <div class="gp-pic-avatar">
                                    {{ strtoupper(substr($pic->nama, 0, 1)) }}
                                </div>

                                <div>

                                    <strong>{{ $pic->nama }}</strong>

                                    @if($pic->jabatan)
                                        <small>{{ $pic->jabatan }}</small>
                                    @else
                                        <small>Belum ada jabatan</small>
                                    @endif

                                </div>

                            </div>

                        </td>

                        {{-- EMAIL --}}
                        <td>

                            @if($pic->email)

                                <div class="gp-info">

                                    <i class="fas fa-envelope"></i>

                                    <span>{{ $pic->email }}</span>

                                </div>

                            @else

                                <span class="gp-empty-text">-</span>

                            @endif

                        </td>

                        {{-- HP --}}
                        <td>

                            @if($pic->no_hp)

                                <div class="gp-info">

                                    <i class="fas fa-phone-alt"></i>

                                    <span>{{ $pic->no_hp }}</span>

                                </div>

                            @else

                                <span class="gp-empty-text">-</span>

                            @endif

                        </td>

                        {{-- JABATAN --}}
                        <td>

                            @if($pic->jabatan)

                                <span class="gp-role-badge">
                                    {{ $pic->jabatan }}
                                </span>

                            @else

                                <span class="gp-empty-text">-</span>

                            @endif

                        </td>

                        {{-- UNIT --}}
                        <td class="text-center">

                            <span class="gp-unit-badge">

                                <i class="fas fa-car"></i>

                                {{ $jumlahUnit }} Unit

                            </span>

                        </td>

                        {{-- AKSI --}}
                        <td>

                            <div class="gp-actions">

                                <a href="{{ route('pic.show', $pic->id) }}" class="gp-action-btn info" title="Detail">

                                    <i class="fas fa-eye"></i>

                                </a>

                                <a href="{{ route('pic.edit', $pic->id) }}" class="gp-action-btn edit" title="Edit">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <form action="{{ route('pic.destroy', $pic->id) }}" method="POST" class="gp-delete-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="gp-action-btn delete"
                                        onclick="return confirm('Yakin ingin menghapus PIC {{ $pic->nama }}?')">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="gp-empty-state">

                                <div class="gp-empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>

                                <strong>Belum Ada Data PIC</strong>

                                <span>Silakan tambahkan PIC terlebih dahulu.</span>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}

    <div class="gp-table-footer">

        <div class="gp-pagination-info">

            Menampilkan

            <strong>{{ $pics->firstItem() ?? 0 }}</strong>

            -

            <strong>{{ $pics->lastItem() ?? 0 }}</strong>

            dari

            <strong>{{ $pics->total() }}</strong>

            data.

        </div>

        <div class="gp-pagination">

            {{ $pics->onEachSide(1)->links('pagination::bootstrap-4') }}

        </div>

    </div>

</div>

@stop