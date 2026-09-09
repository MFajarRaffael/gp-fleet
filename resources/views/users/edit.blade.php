@extends('adminlte::page')

@section('title', 'Edit Akun')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-user-edit"></i>
        </div>

        <div>
            <h1>Edit Akun Pengguna</h1>
            <p>Perbarui informasi akun pengguna GP Fleet Management System PT Green Planet Indonesia.</p>
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

            <strong>Perubahan belum dapat disimpan.</strong>

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

    {{-- HEADER --}}
    <div class="gp-form-header">

        <div class="gp-form-title">

            <div class="gp-form-title-icon">
                <i class="fas fa-user-edit"></i>
            </div>

            <div>
                <h5>Informasi Akun Pengguna</h5>
                <span>Perbarui informasi akun sesuai data terbaru.</span>
            </div>

        </div>

    </div>


    <form action="{{ route('users.update', $user) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="gp-form-body">

            <div class="row">

                {{-- NAMA --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-user"></i>
                            Nama Lengkap
                        </label>

                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
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

                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            placeholder="Contoh: nama@greenplanet.co.id" required>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>


                {{-- PASSWORD BARU --}}
                <div class="col-lg-6">

                    <div class="form-group">

                        <label>
                            <i class="fas fa-lock"></i>
                            Password Baru
                        </label>

                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak ingin mengganti password">

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
                            Konfirmasi Password Baru
                        </label>

                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru">

                    </div>

                </div>


                {{-- INFO CARD --}}
                <div class="col-lg-12">

                    <div class="gp-info-card">

                        <div class="gp-info-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>

                        <div>

                            <strong>Informasi Keamanan Akun</strong>

                            <p>
                                Jika password tidak diisi, sistem akan tetap menggunakan password lama. Isi password
                                baru hanya jika ingin menggantinya.
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

                Update Akun

            </button>

            <a href="{{ route('users.index') }}" class="gp-btn gp-btn-outline">

                <i class="fas fa-times"></i>

                Batal

            </a>

        </div>

    </form>

</div>

@stop