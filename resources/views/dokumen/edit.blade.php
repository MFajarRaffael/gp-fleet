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

        <a href="{{ route('dokumen.index', request()->only('search', 'status', 'jenis', 'page')) }}"
            class="gp-btn gp-btn-outline">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
    @stop

    @section('content')
    @php
        $jenisDokumenStandar = [
            'STNK',
            'KIR',
            'BPKB',
            'Pajak Kendaraan',
            'Surat Jalan',
            'SILO',
        ];

        $jenisDokumenSaatIni = old('jenis_dokumen', $dokumen->jenis_dokumen);

        $jenisDokumenKhusus = !in_array(
            $jenisDokumenSaatIni,
            array_merge($jenisDokumenStandar, ['Dokumen Lainnya']),
            true
        );

        $pilihanJenisDokumen = $jenisDokumenKhusus
            ? 'Dokumen Lainnya'
            : $jenisDokumenSaatIni;

        $namaJenisDokumenKhusus = old(
            'jenis_dokumen_lainnya',
            $jenisDokumenKhusus ? $dokumen->jenis_dokumen : ''
        );
    @endphp

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

        <form action="{{ route('dokumen.update', array_merge(
        ['dokumen' => $dokumen->id],
        request()->only('search', 'status', 'jenis', 'page')
    )) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="gp-form-body">
                <div class="row">
                    {{-- UNIT KENDARAAN --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>
                                <i class="fas fa-car"></i>
                                Unit Kendaraan
                            </label>

                            <select class="form-control" disabled>
                                <option selected>
                                    {{ $dokumen->kendaraan->nomor_unit ?? 'Unit tidak tersedia' }}
                                    - {{ $dokumen->kendaraan->tipe ?? '-' }}
                                    - {{ $dokumen->kendaraan->plat_nomor ?? 'Tanpa Plat' }}
                                </option>
                            </select>

                            <input type="hidden" name="kendaraan_id" value="{{ $dokumen->kendaraan_id }}">
                        </div>
                    </div>

                    {{-- JENIS DOKUMEN --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="jenis_dokumen">
                                <i class="fas fa-file-alt"></i>
                                Jenis Dokumen
                            </label>

                            <select name="jenis_dokumen" id="jenis_dokumen" class="form-control" required>
                                <option value="">-- Pilih Jenis Dokumen --</option>

                                @foreach($jenisDokumenStandar as $jenis)
                                    <option value="{{ $jenis }}" {{ $pilihanJenisDokumen === $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach

                                <option value="Dokumen Lainnya" {{ $pilihanJenisDokumen === 'Dokumen Lainnya' ? 'selected' : '' }}>
                                    Dokumen Lainnya
                                </option>
                            </select>

                            @error('jenis_dokumen')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- NAMA JENIS DOKUMEN KHUSUS --}}
                    <div class="col-lg-12" id="jenis_dokumen_lainnya_wrapper"
                        style="{{ $pilihanJenisDokumen === 'Dokumen Lainnya' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="jenis_dokumen_lainnya">
                                <i class="fas fa-pen"></i>
                                Nama Jenis Dokumen
                            </label>

                            <input type="text" name="jenis_dokumen_lainnya" id="jenis_dokumen_lainnya" class="form-control"
                                value="{{ $namaJenisDokumenKhusus }}" placeholder="Masukkan nama jenis dokumen"
                                maxlength="100" {{ $pilihanJenisDokumen === 'Dokumen Lainnya' ? 'required' : '' }}>

                            @error('jenis_dokumen_lainnya')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- NOMOR DOKUMEN --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="nomor_dokumen">
                                <i class="fas fa-hashtag"></i>
                                Nomor Dokumen
                            </label>

                            <input type="text" name="nomor_dokumen" id="nomor_dokumen" class="form-control"
                                value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen) }}">

                            @error('nomor_dokumen')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- TANGGAL TERBIT --}}
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="tanggal_terbit">
                                <i class="fas fa-calendar-plus"></i>
                                Tanggal Terbit
                            </label>

                            <input type="date" name="tanggal_terbit" id="tanggal_terbit" class="form-control"
                                value="{{ old('tanggal_terbit', optional($dokumen->tanggal_terbit)->format('Y-m-d')) }}">

                            @error('tanggal_terbit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- TANGGAL BERLAKU --}}
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="tanggal_berlaku">
                                <i class="fas fa-calendar-check"></i>
                                Tanggal Berlaku
                            </label>

                            <input type="date" name="tanggal_berlaku" id="tanggal_berlaku" class="form-control"
                                value="{{ old('tanggal_berlaku', optional($dokumen->tanggal_berlaku)->format('Y-m-d')) }}">

                            @error('tanggal_berlaku')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- TANGGAL EXPIRED --}}
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="tanggal_expired">
                                <i class="fas fa-calendar-times"></i>
                                Tanggal Expired
                            </label>

                            <input type="date" name="tanggal_expired" id="tanggal_expired" class="form-control"
                                value="{{ old('tanggal_expired', optional($dokumen->tanggal_expired)->format('Y-m-d')) }}">

                            @error('tanggal_expired')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- FILE SAAT INI --}}
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

                                <a href="{{ asset('storage/' . $dokumen->file) }}" target="_blank" rel="noopener noreferrer"
                                    class="gp-btn gp-btn-outline">
                                    <i class="fas fa-file-download"></i>
                                    Lihat File
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- UPLOAD FILE BARU --}}
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="file">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Upload File Baru
                            </label>

                            <div class="gp-upload-box">
                                <input type="file" name="file" id="file" class="form-control-file"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp">

                                <div class="gp-upload-info">
                                    <i class="fas fa-file-upload"></i>

                                    <div>
                                        <strong>Pilih file baru jika ingin mengganti.</strong>
                                        <p>Kosongkan jika dokumen lama tetap digunakan.</p>
                                        <p>Format: PDF, JPG, JPEG, PNG, atau WEBP. Maksimal 20 MB.</p>
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

            {{-- TOMBOL AKSI --}}
            <div class="gp-form-footer">
                <button type="submit" class="gp-btn gp-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Dokumen
                </button>

                <a href="{{ route('dokumen.index', request()->only('search', 'status', 'jenis', 'page')) }}"
                    class="gp-btn gp-btn-outline">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
    @stop

    @section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenisDokumen = document.getElementById('jenis_dokumen');
            const wrapperLainnya = document.getElementById('jenis_dokumen_lainnya_wrapper');
            const inputLainnya = document.getElementById('jenis_dokumen_lainnya');

            function toggleJenisDokumenLainnya() {
                const tampilkan = jenisDokumen.value === 'Dokumen Lainnya';

                wrapperLainnya.style.display = tampilkan ? '' : 'none';
                inputLainnya.required = tampilkan;

                if (!tampilkan) {
                    inputLainnya.value = '';
                }
            }

            jenisDokumen.addEventListener('change', toggleJenisDokumenLainnya);
            toggleJenisDokumenLainnya();
        });
    </script>
@stop