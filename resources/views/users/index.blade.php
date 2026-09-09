@extends('adminlte::page')

@section('title', 'Manajemen Akun')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-users-cog"></i>
        </div>

        <div>
            <h1>Manajemen Akun</h1>
            <p>Kelola akun pengguna yang dapat mengakses GP Fleet Management System PT Green Planet Indonesia.</p>
        </div>

    </div>

    <a href="{{ route('users.create') }}" class="gp-btn gp-btn-primary">
        <i class="fas fa-user-plus"></i>
        Tambah Akun
    </a>

</div>

@stop


@section('content')

{{-- ============================= --}}
{{-- ALERT --}}
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

        <button class="close gp-alert-close" data-dismiss="alert">&times;</button>

    </div>

@endif


@if(session('error'))

    <div class="gp-alert gp-alert-danger">

        <div class="gp-alert-icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>

        <div class="gp-alert-content">
            <strong>Terjadi Kesalahan</strong>
            <span>{{ session('error') }}</span>
        </div>

        <button class="close gp-alert-close" data-dismiss="alert">&times;</button>

    </div>

@endif


{{-- ============================= --}}
{{-- SUMMARY --}}
{{-- ============================= --}}

@php

    $totalUser = $users->total();

    $akunSaya = $users->where('id', auth()->id())->count();

    $akunLain = $totalUser - $akunSaya;

@endphp

<div class="row mb-4">

    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card green">

            <div>
                <span>Total Akun</span>
                <h2>{{ $totalUser }}</h2>
                <small>Seluruh pengguna sistem</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-users"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card success">

            <div>
                <span>Akun Saya</span>
                <h2>{{ $akunSaya }}</h2>
                <small>Akun yang sedang login</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-user-check"></i>
            </div>

        </div>

    </div>


    <div class="col-lg-4 col-md-6 mb-3">

        <div class="gp-summary-card blue">

            <div>
                <span>Akun Lain</span>
                <h2>{{ $akunLain }}</h2>
                <small>Pengguna lainnya</small>
            </div>

            <div class="gp-summary-icon">
                <i class="fas fa-user-friends"></i>
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
                <h5>Daftar Akun Pengguna</h5>
                <span>Semua akun yang memiliki akses ke GP Fleet.</span>
            </div>

        </div>

        <div class="gp-data-count">
            <strong>{{ $users->total() }}</strong>
            <span>Akun</span>
        </div>

    </div>


    <div class="gp-table-wrapper">

        <table class="gp-table">

            <thead>

                <tr>

                    <th width="60">No</th>

                    <th width="320">Pengguna</th>

                    <th>Email</th>

                    <th width="150">Status</th>

                    <th width="140">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>

                        <td class="gp-number">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        {{-- USER --}}
                        <td>

                            <div class="gp-user">

                                <div class="gp-user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div>

                                    <strong>{{ $user->name }}</strong>

                                    @if($user->id == auth()->id())

                                        <small class="gp-my-account">
                                            <i class="fas fa-user-check"></i>
                                            Akun Saya
                                        </small>

                                    @else

                                        <small>Pengguna GP Fleet</small>

                                    @endif

                                </div>

                            </div>

                        </td>


                        {{-- EMAIL --}}
                        <td>

                            <div class="gp-info">

                                <i class="fas fa-envelope"></i>

                                <span>{{ $user->email }}</span>

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($user->id == auth()->id())

                                <span class="gp-status active">

                                    <span class="gp-status-dot"></span>

                                    Sedang Login

                                </span>

                            @else

                                <span class="gp-status inactive">

                                    <span class="gp-status-dot"></span>

                                    Pengguna

                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <div class="gp-actions">

                                <a href="{{ route('users.edit', $user) }}" class="gp-action-btn edit" title="Edit">

                                    <i class="fas fa-edit"></i>

                                </a>

                                @if($user->id !== auth()->id())

                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="gp-delete-form"
                                        onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="gp-action-btn delete">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5">

                            <div class="gp-empty-state">

                                <div class="gp-empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>

                                <strong>Belum Ada Akun</strong>

                                <span>Silakan tambahkan akun pengguna terlebih dahulu.</span>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    <div class="gp-table-footer">

        <div class="gp-pagination-info">

            Menampilkan

            <strong>{{ $users->firstItem() ?? 0 }}</strong>

            -

            <strong>{{ $users->lastItem() ?? 0 }}</strong>

            dari

            <strong>{{ $users->total() }}</strong>

            data.

        </div>

        <div class="gp-pagination">
            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>

    </div>

</div>

@stop