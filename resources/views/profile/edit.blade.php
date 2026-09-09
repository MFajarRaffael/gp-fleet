@extends('adminlte::page')

@section('title', 'Account Settings')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-user-cog"></i>
        </div>

        <div>
            <h1>Account Settings</h1>
            <p>Kelola informasi akun dan keamanan akun GP Fleet Management System PT Green Planet Indonesia.</p>
        </div>

    </div>

</div>

@stop


@section('content')

@if(session('success'))

    <div class="gp-alert gp-alert-success">

        <div class="gp-alert-icon">
            <i class="fas fa-check-circle"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Berhasil</strong>
            <span>{{ session('success') }}</span>
        </div>

    </div>

@endif


@if($errors->any())

    <div class="gp-alert gp-alert-danger">

        <div class="gp-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>

        <div class="gp-alert-content">

            <strong>Periksa kembali data.</strong>

            <span>Masih ada beberapa informasi yang belum sesuai.</span>

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- PROFILE HEADER --}}
{{-- ========================================================= --}}

<div class="gp-profile-banner mb-4">

    <div class="gp-profile-avatar">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>

    <div class="gp-profile-banner-info">

        <h2>{{ $user->name }}</h2>

        <p>{{ $user->email }}</p>

        <span class="gp-role-badge">
            <i class="fas fa-user-shield mr-1"></i>
            Administrator GP Fleet
        </span>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SUMMARY CARD --}}
{{-- ========================================================= --}}

<div class="row mb-4">

    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card green">

            <div>
                <span>Status Akun</span>
                <h2>Aktif</h2>
                <small>Dapat mengakses sistem GP Fleet.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card blue">

            <div>
                <span>Email Terdaftar</span>
                <h2>1</h2>
                <small>Email aktif untuk login.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-envelope"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card success">

            <div>
                <span>Keamanan Akun</span>
                <h2>Aman</h2>
                <small>Password dapat diperbarui kapan saja.</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-lock"></i>
            </div>

        </div>

    </div>

</div>


<div class="row">

    {{-- ========================================================= --}}
    {{-- PROFILE INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="col-lg-6 mb-4">

        <div class="gp-form-card">

            <div class="gp-form-header">

                <div class="gp-form-title">

                    <div class="gp-form-title-icon">
                        <i class="fas fa-user"></i>
                    </div>

                    <div>
                        <h5>Profile Information</h5>
                        <span>Perbarui nama dan email akun Anda.</span>
                    </div>

                </div>

            </div>


            <form method="POST" action="{{ route('profile.update') }}">

                @csrf
                @method('PATCH')

                <div class="gp-form-body">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-user"></i>
                            Nama Lengkap
                        </label>

                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap" required>

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>

                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            placeholder="Masukkan email aktif" required>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>


                    <div class="gp-info-card">

                        <div class="gp-info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>
                            <strong>Informasi Profil</strong>

                            <p>
                                Email yang diperbarui akan digunakan sebagai akun login GP Fleet Management System.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="gp-form-footer">

                    <button class="gp-btn gp-btn-primary">

                        <i class="fas fa-save"></i>

                        Simpan Profil

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CHANGE PASSWORD --}}
    {{-- ========================================================= --}}

    <div class="col-lg-6 mb-4">

        <div class="gp-form-card">

            <div class="gp-form-header">

                <div class="gp-form-title">

                    <div class="gp-form-title-icon">
                        <i class="fas fa-lock"></i>
                    </div>

                    <div>
                        <h5>Change Password</h5>
                        <span>Perbarui password akun agar tetap aman.</span>
                    </div>

                </div>

            </div>


            <form method="POST" action="{{ route('profile.password') }}">

                @csrf
                @method('PUT')

                <div class="gp-form-body">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-key"></i>
                            Password Saat Ini
                        </label>

                        <input type="password" name="current_password" class="form-control"
                            placeholder="Masukkan password saat ini" required>

                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-lock"></i>
                            Password Baru
                        </label>

                        <input type="password" name="password" class="form-control" placeholder="Masukkan password baru"
                            required>

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label>
                            <i class="fas fa-lock"></i>
                            Konfirmasi Password Baru
                        </label>

                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru" required>

                    </div>


                    <div class="gp-info-card">

                        <div class="gp-info-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>

                        <div>

                            <strong>Keamanan Password</strong>

                            <p>
                                Gunakan password minimal 8 karakter dan kombinasi huruf, angka, atau simbol agar akun
                                lebih aman.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="gp-form-footer">

                    <button class="gp-btn gp-btn-warning">

                        <i class="fas fa-key"></i>

                        Ubah Password

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@stop