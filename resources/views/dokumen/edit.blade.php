@extends('adminlte::page')

@section('title', 'Edit Dokumen Kendaraan')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dokumen.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-edit"></i>
        </div>

        <div>
            <h1>Edit Dokumen Kendaraan</h1>
            <p>Perbarui informasi dokumen kendaraan PT Green Planet Indonesia.</p>
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
            <strong>Perubahan belum dapat disimpan.</strong>
            <span>Silakan periksa kembali data yang masih belum sesuai.</span>
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
                <span>Perbarui informasi dokumen sesuai data kendaraan.</span>
            </div>

        </div>

    </div>


    <form action="{{ route('dokumen.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="gp-form-body">

            <div class="row">

                {{-- Unit --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-car"></i>
                            Unit Kendaraan
                        </label>

                        <select name="kendaraan_id" class="form-control" required>

                            @foreach($kendaraans as $k)

                                <option value="{{ $k->id }}" {{ old('kendaraan_id', $dokumen->kendaraan_id) == $k->id ? 'selected' : '' }}>

                                    {{ $k->nomor_unit }}
                                    -
                                    {{ $k->tipe }}
                                    -
                                    {{ $k->plat_nomor ?: 'Tanpa Plat' }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Jenis --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-file-alt"></i>
                            Jenis Dokumen
                        </label>

                        <select name="jenis_dokumen" class="form-control" required>

                            @foreach([
                                    'STNK',
                                    'KIR',
                                    'BPKB',
                                    'Pajak Kendaraan',
                                    'Surat Jalan',
                                    'Dokumen Lainnya'
                                ] as $jenis)

                                <option value="{{ $jenis }}" {{ old('jenis_dokumen', $dokumen->jenis_dokumen) == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Nomor --}}
                <div class="col-lg-12">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-hashtag"></i>
                            Nomor Dokumen
                        </label>

                        <input type="text" name="nomor_dokumen" class="form-control"
                            value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen) }}">

                        @error('nomor_dokumen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- Tanggal --}}
                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-plus"></i>
                            Tanggal Terbit
                        </label>

                        <input type="date" name="tanggal_terbit" class="form-control"
                            value="{{ old('tanggal_terbit', optional($dokumen->tanggal_terbit)->format('Y-m-d')) }}">

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-check"></i>
                            Tanggal Berlaku
                        </label>

                        <input type="date" name="tanggal_berlaku" class="form-control"
                            value="{{ old('tanggal_berlaku', optional($dokumen->tanggal_berlaku)->format('Y-m-d')) }}">

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-calendar-times"></i>
                            Tanggal Expired
                        </label>

                        <input type="date" name="tanggal_expired" class="form-control"
                            value="{{ old('tanggal_expired', optional($dokumen->tanggal_expired)->format('Y-m-d')) }}">

                    </div>

                </div>


                {{-- FILE SEKARANG --}}
                <div class="col-lg-12">

                    @if($dokumen->file)

                        <div class="gp-current-file">

                            <div class="gp-current-file-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>

                            <div class="gp-current-file-info">
                                <strong>File Dokumen Saat Ini</strong>
                                <p>Dokumen yang tersimpan pada sistem.</p>
                            </div>

                            <a href="{{ asset('storage/' . $dokumen->file) }}" target="_blank" class="gp-btn gp-btn-outline">

                                <i class="fas fa-file-download"></i>
                                Lihat File

                            </a>

                        </div>

                    @endif

                </div>


                {{-- Upload Baru --}}
                <div class="col-lg-12">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-cloud-upload-alt"></i>
                            Upload File Baru
                        </label>

                        <div class="gp-upload-box">

                            <input type="file" name="file" class="form-control-file"
                                accept=".pdf,.jpg,.jpeg,.png,.webp">

                            <div class="gp-upload-info">

                                <i class="fas fa-file-upload"></i>

                                <div>
                                    <strong>Pilih file baru jika ingin mengganti.</strong>
                                    <p>Kosongkan jika dokumen lama tetap digunakan.</p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="gp-form-footer">

            <button type="submit" class="gp-btn gp-btn-primary">

                <i class="fas fa-save"></i>

                Update Dokumen

            </button>

            <a href="{{ route('dokumen.index') }}" class="gp-btn gp-btn-outline">

                <i class="fas fa-times"></i>

                Batal

            </a>

        </div>

    </form>

</div>

@stop