@extends('adminlte::page')

@section('title', 'Tambah Dokumen Kendaraan')

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
            <h1>Tambah Dokumen Kendaraan</h1>
            <p>Tambahkan dokumen kendaraan, heavy vehicle, dan heavy equipment PT Green Planet Indonesia.</p>
        </div>

    </div>

    <a href="{{ route('dokumen.index') }}" class="gp-btn gp-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

</div>

@stop


@section('content')

@if ($errors->any())

    <div class="gp-alert gp-alert-danger">

        <div class="gp-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Data belum lengkap.</strong>
            <span>Silakan periksa kembali form yang masih belum sesuai.</span>
        </div>

    </div>

@endif


<div class="gp-form-card">

    <div class="gp-form-header">

        <div class="gp-form-title">

            <div class="gp-form-title-icon">
                <i class="fas fa-file-alt"></i>
            </div>

            <div>
                <h5>Informasi Dokumen</h5>
                <span>Isi informasi dokumen sesuai data kendaraan.</span>
            </div>

        </div>

    </div>


    <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="gp-form-body">

            <div class="row">

                {{-- ===================== --}}
                {{-- KENDARAAN --}}
                {{-- ===================== --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-car"></i>
                            Kendaraan
                        </label>

                        @if(isset($kendaraanTerpilih) && $kendaraanTerpilih)

                            {{-- Jika dari halaman Show Unit --}}
                            <select class="form-control" disabled>

                                @foreach($kendaraans as $k)

                                    @if($k->id == $kendaraanTerpilih)

                                        <option selected>
                                            {{ $k->nomor_unit }}
                                            -
                                            {{ $k->tipe }}
                                            -
                                            {{ $k->plat_nomor ?: 'Tanpa Plat' }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                            <input type="hidden" name="kendaraan_id" value="{{ $kendaraanTerpilih }}">

                            <small class="text-muted mt-2">
                                Dokumen akan ditambahkan untuk unit ini.
                            </small>

                        @else

                            {{-- Jika dari menu Dokumen --}}
                            <select name="kendaraan_id" class="form-control" required>

                                <option value="">-- Pilih Kendaraan --</option>

                                @foreach($kendaraans as $k)

                                    <option value="{{ $k->id }}" {{ old('kendaraan_id') == $k->id ? 'selected' : '' }}>

                                        {{ $k->nomor_unit }}
                                        -
                                        {{ $k->tipe }}
                                        -
                                        {{ $k->plat_nomor ?: 'Tanpa Plat' }}

                                    </option>

                                @endforeach

                            </select>

                        @endif

                        @error('kendaraan_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- JENIS DOKUMEN --}}
                {{-- ===================== --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-file-alt"></i>
                            Jenis Dokumen
                        </label>

                        <select name="jenis_dokumen" class="form-control" required>

                            <option value="">-- Pilih Jenis Dokumen --</option>

                            @foreach([
                                    'STNK',
                                    'KIR',
                                    'BPKB',
                                    'Pajak Kendaraan',
                                    'Surat Jalan',
                                    'Dokumen Lainnya'
                                ] as $jenis)

                                <option value="{{ $jenis }}" {{ old('jenis_dokumen') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>

                            @endforeach

                        </select>

                        @error('jenis_dokumen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- NOMOR DOKUMEN --}}
                {{-- ===================== --}}
                <div class="col-lg-12">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-hashtag"></i>
                            Nomor Dokumen
                        </label>

                        <input type="text" name="nomor_dokumen" class="form-control" value="{{ old('nomor_dokumen') }}"
                            placeholder="Masukkan nomor dokumen">

                        @error('nomor_dokumen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- TANGGAL TERBIT --}}
                {{-- ===================== --}}
                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-plus"></i>
                            Tanggal Terbit
                        </label>

                        <input type="date" name="tanggal_terbit" class="form-control"
                            value="{{ old('tanggal_terbit') }}">

                        @error('tanggal_terbit')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- TANGGAL BERLAKU --}}
                {{-- ===================== --}}
                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-check"></i>
                            Tanggal Berlaku
                        </label>

                        <input type="date" name="tanggal_berlaku" class="form-control"
                            value="{{ old('tanggal_berlaku') }}">

                        @error('tanggal_berlaku')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- TANGGAL EXPIRED --}}
                {{-- ===================== --}}
                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-times"></i>
                            Tanggal Expired
                        </label>

                        <input type="date" name="tanggal_expired" class="form-control"
                            value="{{ old('tanggal_expired') }}">

                        @error('tanggal_expired')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- ===================== --}}
                {{-- FILE DOKUMEN --}}
                {{-- ===================== --}}
                <div class="col-lg-12">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-cloud-upload-alt"></i>
                            Upload File Dokumen
                        </label>

                        <div class="gp-upload-box">

                            <input type="file" name="file" class="form-control-file"
                                accept=".pdf,.jpg,.jpeg,.png,.webp">

                            <div class="gp-upload-info">

                                <i class="fas fa-file-upload"></i>

                                <div>
                                    <strong>Pilih file dokumen</strong>
                                    <p>Format PDF, JPG, JPEG, PNG, WEBP • Maksimal 5 MB.</p>
                                </div>

                            </div>

                        </div>

                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        <div class="gp-form-footer">

            <button type="submit" class="gp-btn gp-btn-primary">
                <i class="fas fa-save"></i>
                Simpan Dokumen
            </button>

            <a href="{{ route('dokumen.index') }}" class="gp-btn gp-btn-outline">
                <i class="fas fa-times"></i>
                Batal
            </a>

        </div>

    </form>

</div>

@stop