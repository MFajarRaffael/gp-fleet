@extends('adminlte::page')

@section('title', 'Data Kendaraan')

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-car-side"></i>
        </div>

        <div>
            <h1>Data Unit</h1>

            <p>
                Kelola data kendaraan, heavy vehicle, dan heavy equipment
            </p>
        </div>

    </div>

    <a href="{{ route('kendaraan.create') }}" class="gp-btn gp-btn-primary">

        <i class="fas fa-plus"></i>

        Tambah Unit

    </a>

</div>

@stop


@section('content')


{{-- =========================================================
SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))

    <div class="gp-alert gp-alert-success">

        <div class="gp-alert-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="gp-alert-content">

            <strong>Berhasil</strong>

            <span>
                {{ session('success') }}
            </span>

        </div>

        <button type="button" class="gp-alert-close close" data-dismiss="alert">

            <span>&times;</span>

        </button>

    </div>

@endif


{{-- =========================================================
FILTER
========================================================= --}}

<div class="gp-filter-card">

    <div class="gp-filter-header">

        <div class="gp-filter-title">

            <div class="gp-filter-icon">
                <i class="fas fa-sliders-h"></i>
            </div>

            <div>

                <h5>Filter Data</h5>

                <span>
                    Filter berdasarkan kategori unit
                </span>

            </div>

        </div>

    </div>


    <form method="GET" action="{{ route('kendaraan.index') }}">

        <div class="gp-filter-body">

            <div class="gp-form-group">

                <label for="kategori">
                    Kategori Unit
                </label>

                <select name="kategori" id="kategori" class="form-control gp-select">

                    <option value="">
                        Semua Kategori
                    </option>

                    <option value="Kendaraan" {{ request('kategori') == 'Kendaraan' ? 'selected' : '' }}>
                        Kendaraan
                    </option>

                    <option value="HV" {{ request('kategori') == 'HV' ? 'selected' : '' }}>
                        Heavy Vehicle (HV)
                    </option>

                    <option value="HE" {{ request('kategori') == 'HE' ? 'selected' : '' }}>
                        Heavy Equipment (HE)
                    </option>

                </select>

            </div>


            <div class="gp-filter-actions">

                <button type="submit" class="gp-btn gp-btn-primary">

                    <i class="fas fa-search"></i>

                    Filter

                </button>


                <a href="{{ route('kendaraan.index') }}" class="gp-btn gp-btn-light">

                    <i class="fas fa-rotate-right"></i>

                    Reset

                </a>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
DATA UNIT
========================================================= --}}

<div class="gp-table-card">

    <div class="gp-table-header">

        <div class="gp-table-title">

            <div class="gp-table-title-icon">
                <i class="fas fa-list"></i>
            </div>

            <div>

                <h5>Daftar Unit</h5>

                <span>
                    Seluruh unit yang terdaftar di sistem
                </span>

            </div>

        </div>


        <div class="gp-data-count">

            <strong>
                {{ $kendaraans->total() }}
            </strong>

            <span>
                Data
            </span>

        </div>

    </div>


    <div class="gp-table-wrapper">

        <div class="table-responsive">

            <table class="table gp-table">

                <thead>

                    <tr>

                        <th class="text-center gp-no">
                            No
                        </th>

                        <th class="gp-photo">
                            Foto
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th>
                            Tipe
                        </th>

                        <th>
                            Plat Nomor
                        </th>

                        <th>
                            Nomor Unit
                        </th>

                        <th>
                            Merk
                        </th>

                        <th>
                            Tahun
                        </th>

                        <th>
                            PIC
                        </th>

                        <th>
                            Project
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-center gp-action">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($kendaraans as $kendaraan)

                        <tr>

                            {{-- NO --}}

                            <td class="text-center gp-number">
                                {{ $kendaraans->firstItem() + $loop->index }}
                            </td>


                            {{-- FOTO --}}

                            <td>

                                @if($kendaraan->foto)

                                    <img src="{{ asset('storage/' . $kendaraan->foto) }}"
                                        alt="Foto {{ $kendaraan->nomor_unit }}" class="gp-vehicle-photo">

                                @else

                                    <div class="gp-no-photo">
                                        <i class="fas fa-car"></i>
                                    </div>

                                @endif

                            </td>


                            {{-- KATEGORI --}}

                            <td>

                                @if($kendaraan->kategori == 'Kendaraan')

                                    <span class="gp-badge gp-badge-green">
                                        <i class="fas fa-car-side"></i>
                                        Kendaraan
                                    </span>

                                @elseif($kendaraan->kategori == 'HV')

                                    <span class="gp-badge gp-badge-blue">
                                        <i class="fas fa-truck-moving"></i>
                                        HV
                                    </span>

                                @elseif($kendaraan->kategori == 'HE')

                                    <span class="gp-badge gp-badge-gray">
                                        <i class="fas fa-tractor"></i>
                                        HE
                                    </span>

                                @else

                                    <span class="gp-badge gp-badge-neutral">
                                        {{ $kendaraan->kategori }}
                                    </span>

                                @endif

                            </td>


                            {{-- TIPE --}}

                            <td>
                                <span class="gp-main-text">
                                    {{ $kendaraan->tipe }}
                                </span>
                            </td>


                            {{-- PLAT --}}

                            <td>

                                @if($kendaraan->plat_nomor)

                                    <span class="gp-plate">
                                        {{ $kendaraan->plat_nomor }}
                                    </span>

                                @else

                                    <span class="gp-empty">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- NOMOR UNIT --}}

                            <td>

                                <strong class="gp-unit-number">
                                    {{ $kendaraan->nomor_unit }}
                                </strong>

                            </td>


                            {{-- MERK --}}

                            <td>
                                <span class="gp-main-text">
                                    {{ $kendaraan->merk }}
                                </span>
                            </td>


                            {{-- TAHUN --}}

                            <td>
                                <span class="gp-year">
                                    {{ $kendaraan->tahun }}
                                </span>
                            </td>


                            {{-- PIC --}}

                            <td>

                                @if($kendaraan->pic)

                                    <div class="gp-pic">

                                        <div class="gp-pic-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <span>
                                            {{ $kendaraan->pic->nama }}
                                        </span>

                                    </div>

                                @else

                                    <span class="gp-empty">
                                        Belum ada PIC
                                    </span>

                                @endif

                            </td>


                            {{-- PROJECT --}}

                            <td>

                                @if($kendaraan->project)

                                    <span class="gp-project">
                                        {{ $kendaraan->project }}
                                    </span>

                                @else

                                    <span class="gp-empty">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}

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


                            {{-- AKSI --}}

                            <td>

                                <div class="gp-actions">

                                    <a href="{{ route('kendaraan.show', $kendaraan->id) }}" class="gp-action-btn detail"
                                        title="Detail">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="gp-action-btn edit"
                                        title="Edit">

                                        <i class="fas fa-pen"></i>

                                    </a>


                                    <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST"
                                        class="gp-delete-form">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="gp-action-btn delete" title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus unit ini?')">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="12">

                                <div class="gp-empty-state">

                                    <div class="gp-empty-icon">
                                        <i class="fas fa-car"></i>
                                    </div>

                                    <strong>
                                        Belum Ada Data Unit
                                    </strong>

                                    <span>
                                        Belum terdapat data kendaraan yang tersedia.
                                    </span>

                                    <a href="{{ route('kendaraan.create') }}" class="gp-btn gp-btn-primary">

                                        <i class="fas fa-plus"></i>

                                        Tambah Unit

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}

    @if($kendaraans->hasPages() || $kendaraans->total() > 0)

        <div class="gp-table-footer">

            <div class="gp-pagination-info">

                Menampilkan

                <strong>
                    {{ $kendaraans->firstItem() ?? 0 }}
                </strong>

                -

                <strong>
                    {{ $kendaraans->lastItem() ?? 0 }}
                </strong>

                dari

                <strong>
                    {{ $kendaraans->total() }}
                </strong>

                data

            </div>


            <div class="gp-pagination">

                {{ $kendaraans->onEachSide(1)->links('pagination::bootstrap-4') }}

            </div>

        </div>

    @endif

</div>


@stop


@section('css')

<link rel="stylesheet" href="{{ asset('css/kendaraan.css') }}">

@stop