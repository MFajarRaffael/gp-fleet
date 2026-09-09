@extends('adminlte::page')

@section('title', 'Edit PIC')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pic.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-user-edit"></i>
        </div>

        <div>
            <h1>Edit Person In Charge</h1>
            <p>Perbarui informasi PIC yang bertanggung jawab terhadap kendaraan PT Green Planet Indonesia.</p>
        </div>

    </div>

    <a href="{{ route('pic.index') }}" class="gp-btn gp-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

</div>

@stop


@section('content')

{{-- ALERT ERROR --}}
@if($errors->any())

<div class="gp-alert gp-alert-danger">

    <div class="gp-alert-icon">
        <i class="fas fa-exclamation-circle"></i>
    </div>

    <div class="gp-alert-content">
        <strong>Perubahan belum dapat disimpan.</strong>
        <span>Silakan periksa kembali informasi PIC yang masih belum sesuai.</span>

        <ul class="gp-error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

</div>

@endif


<div class="gp-form-card">

    <div class="gp-form-header">

        <div class="gp-form-title">

            <div class="gp-form-title-icon">
                <i class="fas fa-user-edit"></i>
            </div>

            <div>
                <h5>Informasi Person In Charge</h5>
                <span>Perbarui data PIC sesuai informasi terbaru.</span>
            </div>

        </div>

    </div>


    <form action="{{ route('pic.update', $pic->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="gp-form-body">

            <div class="row">

                {{-- NAMA PIC --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-user"></i>
                            Nama PIC
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="{{ old('nama', $pic->nama) }}"
                            placeholder="Masukkan nama lengkap PIC"
                            required>

                    </div>

                </div>


                {{-- JABATAN --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-briefcase"></i>
                            Jabatan
                        </label>

                        <input
                            type="text"
                            name="jabatan"
                            class="form-control"
                            value="{{ old('jabatan', $pic->jabatan) }}"
                            placeholder="Contoh: Supervisor Operasional">

                    </div>

                </div>


                {{-- EMAIL --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $pic->email) }}"
                            placeholder="Contoh: nama@greenplanet.co.id">

                    </div>

                </div>


                {{-- NO HP --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-phone-alt"></i>
                            Nomor Handphone
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            class="form-control"
                            value="{{ old('no_hp', $pic->no_hp) }}"
                            placeholder="Contoh: 081234567890">

                    </div>

                </div>


                {{-- STATUS INFO --}}
                <div class="col-lg-12">

                    <div class="gp-info-card">

                        <div class="gp-info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>
                            <strong>Informasi PIC</strong>

                            <p>
                                Perubahan data PIC akan langsung diperbarui pada seluruh kendaraan yang terhubung dengan PIC ini.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="gp-form-footer">

            <button type="submit" class="gp-btn gp-btn-primary">

                <i class="fas fa-save"></i>

                Update PIC

            </button>

            <a href="{{ route('pic.index') }}" class="gp-btn gp-btn-outline">

                <i class="fas fa-times"></i>

                Batal

            </a>

        </div>

    </form>

</div>

@stop