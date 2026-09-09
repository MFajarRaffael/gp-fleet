@extends('adminlte::page')

@section('title', 'Tambah Akun')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-user-plus"></i>
        </div>

        <div>
            <h1>Tambah Akun Pengguna</h1>
            <p>Tambahkan akun baru yang dapat mengakses GP Fleet Management System PT Green Planet Indonesia.</p>
        </div>

    </div>

    <a href="{{ route('users.index') }}" class="gp-btn gp-btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

</div>

@stop


@section('content')

{{-- ALERT ERROR --}}
@if ($errors->any())

    <div class="gp-alert gp-alert-danger">

        <div class="gp-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>

        <div class="gp-alert-content">

            <strong>Data belum lengkap.</strong>

            <span>Silakan periksa kembali data akun yang masih belum sesuai.</span>

            <ul class="gp-error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    </div>

@endif


<div class="gp-form-card">

    {{-- HEADER FORM --}}
    <div class="gp-form-header">

        <div class="gp-form-title">

            <div class="gp-form-title-icon">
                <i class="fas fa-user-circle"></i>
            </div>

            <div>
                <h5>Informasi Akun Pengguna</h5>
                <span>Lengkapi informasi akun untuk pengguna GP Fleet.</span>
            </div>

        </div>

    </div>


    <form action="{{ route('users.store') }}" method="POST">

        @csrf

        <div class="gp-form-body">

            <div class="row">

                {{-- NAMA --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-user"></i>
                            Nama Lengkap
                        </label>

                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap pengguna" required>

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- EMAIL --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>

                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="Contoh: nama@greenplanet.co.id" required>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-lock"></i>
                            Password
                        </label>

                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- KONFIRMASI PASSWORD --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-lock"></i>
                            Konfirmasi Password
                        </label>

                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password" required>

                    </div>

                </div>


                {{-- INFO CARD --}}
                <div class="col-lg-12">

                    <div class="gp-info-card">

                        <div class="gp-info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>
                            <strong>Informasi Akun</strong>

                            <p>
                                Akun yang ditambahkan dapat langsung digunakan untuk login ke sistem GP Fleet
                                menggunakan email dan password yang dibuat pada form ini.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="gp-form-footer">

            <button type="submit" class="gp-btn gp-btn-primary">

                <i class="fas fa-save"></i>

                Simpan Akun

            </button>

            <a href="{{ route('users.index') }}" class="gp-btn gp-btn-outline">

                <i class="fas fa-times"></i>

                Batal

            </a>

        </div>

    </form>

</div>

@stop