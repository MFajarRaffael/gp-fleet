@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

<div class="gp-dashboard-header">

    <div class="gp-header-left">
        <div class="gp-welcome-icon">
            <i class="fas fa-leaf"></i>
        </div>

        <div>
            <h2 class="gp-title">
                Selamat Datang, {{ Auth::user()->name }}
            </h2>

            <p class="gp-subtitle">
                GP Fleet Management System
                <span>•</span>
                PT Green Planet Indonesia
            </p>
        </div>
    </div>

    <div class="gp-date">
        <i class="far fa-calendar-alt"></i>
        <span>{{ now()->translatedFormat('d F Y') }}</span>
    </div>

</div>

@stop


@section('content')

{{-- ========================================================= --}}
{{-- SUMMARY KENDARAAN --}}
{{-- ========================================================= --}}

<div class="gp-section-title">
    <div>
        <h5>Ringkasan Armada</h5>
        <span>Informasi jumlah unit yang terdaftar</span>
    </div>
</div>


<div class="row gp-stat-row">

    {{-- Kendaraan --}}
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="gp-stat-card">

            <div class="gp-stat-content">

                <div class="gp-stat-label">
                    KENDARAAN
                </div>

                <div class="gp-stat-number">
                    {{ $totalUmum }}
                </div>

                <div class="gp-stat-description">
                    Unit kendaraan
                </div>

            </div>

            <div class="gp-stat-icon green">
                <i class="fas fa-car-side"></i>
            </div>

        </div>
    </div>


    {{-- Heavy Vehicle --}}
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="gp-stat-card">

            <div class="gp-stat-content">

                <div class="gp-stat-label">
                    HEAVY VEHICLE
                </div>

                <div class="gp-stat-number">
                    {{ $totalHV }}
                </div>

                <div class="gp-stat-description">
                    Unit kendaraan berat
                </div>

            </div>

            <div class="gp-stat-icon dark-green">
                <i class="fas fa-truck-moving"></i>
            </div>

        </div>
    </div>


    {{-- Heavy Equipment --}}
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="gp-stat-card">

            <div class="gp-stat-content">

                <div class="gp-stat-label">
                    HEAVY EQUIPMENT
                </div>

                <div class="gp-stat-number">
                    {{ $totalHE }}
                </div>

                <div class="gp-stat-description">
                    Unit alat berat
                </div>

            </div>

            <div class="gp-stat-icon dark-green">
                <i class="fas fa-tractor"></i>
            </div>

        </div>
    </div>


    {{-- Total Unit --}}
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="gp-stat-card gp-stat-total">

            <div class="gp-stat-content">

                <div class="gp-stat-label">
                    TOTAL UNIT
                </div>

                <div class="gp-stat-number">
                    {{ $totalKendaraan }}
                </div>

                <div class="gp-stat-description">
                    Seluruh unit terdaftar
                </div>

            </div>

            <div class="gp-stat-icon total">
                <i class="fas fa-layer-group"></i>
            </div>

        </div>
    </div>

</div>


{{-- ========================================================= --}}
{{-- STATUS UNIT --}}
{{-- ========================================================= --}}

<div class="gp-section-title mt-2">
    <div>
        <h5>Status Armada</h5>
        <span>Kondisi operasional kendaraan saat ini</span>
    </div>
</div>


<div class="row">

    {{-- Aktif --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="gp-status-card">

            <div class="gp-status-icon active">
                <i class="fas fa-check"></i>
            </div>

            <div class="gp-status-info">

                <span>UNIT AKTIF</span>

                <strong>
                    {{ $kendaraanAktif }}
                </strong>

                <small>
                    Sedang aktif beroperasi
                </small>

            </div>

        </div>
    </div>


    {{-- Tidak Aktif --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="gp-status-card">

            <div class="gp-status-icon inactive">
                <i class="fas fa-times"></i>
            </div>

            <div class="gp-status-info">

                <span>UNIT TIDAK AKTIF</span>

                <strong>
                    {{ $kendaraanTidakAktif }}
                </strong>

                <small>
                    Tidak sedang beroperasi
                </small>

            </div>

        </div>
    </div>


    {{-- Total Dokumen --}}
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="gp-status-card">

            <div class="gp-status-icon document">
                <i class="fas fa-file-alt"></i>
            </div>

            <div class="gp-status-info">

                <span>TOTAL DOKUMEN</span>

                <strong>
                    {{ $totalDokumen }}
                </strong>

                <small>
                    Dokumen kendaraan
                </small>

            </div>

        </div>
    </div>

</div>


{{-- ========================================================= --}}
{{-- MONITORING DOKUMEN --}}
{{-- ========================================================= --}}

<div class="gp-section-title mt-3">
    <div>
        <h5>Monitoring Dokumen</h5>
        <span>Pantau masa berlaku dokumen kendaraan</span>
    </div>
</div>


<div class="row">

    {{-- Expired --}}
    <div class="col-lg-6 mb-3">

        <div class="gp-document-card expired">

            <div class="gp-document-top">

                <div class="gp-document-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div>

                    <span class="gp-document-label">
                        DOKUMEN EXPIRED
                    </span>

                    <div class="gp-document-number">
                        {{ $expired }}
                    </div>

                </div>

            </div>

            <p>
                Dokumen yang masa berlakunya telah habis
                dan perlu segera ditindaklanjuti.
            </p>

            <a href="{{ route('dokumen.index') }}" class="gp-document-button danger">

                Lihat Dokumen
                <i class="fas fa-arrow-right"></i>

            </a>

        </div>

    </div>


    {{-- Akan Expired --}}
    <div class="col-lg-6 mb-3">

        <div class="gp-document-card warning">

            <div class="gp-document-top">

                <div class="gp-document-icon">
                    <i class="fas fa-bell"></i>
                </div>

                <div>

                    <span class="gp-document-label">
                        SEGERA EXPIRED
                    </span>

                    <div class="gp-document-number">
                        {{ $akanExpired }}
                    </div>

                </div>

            </div>

            <p>
                Dokumen yang akan expired dalam
                30 hari dan perlu diperhatikan.
            </p>

            <a href="{{ route('dokumen.index') }}" class="gp-document-button warning">

                Cek Dokumen
                <i class="fas fa-arrow-right"></i>

            </a>

        </div>

    </div>

</div>


@stop


@section('css')

<style>
    /* =========================================================
   GENERAL
========================================================= */

    .content-wrapper {
        background: #f5f7f6 !important;
    }

    .content {
        padding-bottom: 30px;
    }


    /* =========================================================
   HEADER
========================================================= */

    .gp-dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;

        padding: 22px 25px;

        border-radius: 14px;

        border: 1px solid #e8ece9;

        margin-bottom: 25px;

        box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
    }

    .gp-header-left {
        display: flex;
        align-items: center;
    }

    .gp-welcome-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e9f6ee;

        color: #16834a;

        border-radius: 12px;

        margin-right: 15px;

        font-size: 20px;
    }

    .gp-title {
        margin: 0;

        font-size: 22px;

        font-weight: 700;

        color: #1f2933;
    }

    .gp-subtitle {
        margin: 4px 0 0;

        font-size: 13px;

        color: #7b858c;
    }

    .gp-subtitle span {
        margin: 0 5px;

        color: #b1b8bc;
    }

    .gp-date {
        display: flex;
        align-items: center;

        padding: 9px 13px;

        border-radius: 9px;

        background: #f6f8f7;

        color: #6c757d;

        font-size: 13px;

        font-weight: 500;
    }

    .gp-date i {
        color: #16834a;

        font-size: 15px;
    }


    /* =========================================================
   SECTION TITLE
========================================================= */

    .gp-section-title {
        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 12px;

        padding-left: 2px;
    }

    .gp-section-title h5 {
        margin: 0;

        font-size: 16px;

        font-weight: 700;

        color: #273239;
    }

    .gp-section-title span {
        display: block;

        margin-top: 2px;

        font-size: 12px;

        color: #92999e;
    }


    /* =========================================================
   STAT CARD
========================================================= */

    .gp-stat-card {
        position: relative;

        min-height: 145px;

        background: #ffffff;

        border: 1px solid #e8ece9;

        border-radius: 14px;

        padding: 22px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        overflow: hidden;

        transition: all .2s ease;
    }

    .gp-stat-card:hover {
        transform: translateY(-2px);

        box-shadow: 0 8px 22px rgba(0, 0, 0, .07);

        border-color: #d9e4dd;
    }

    .gp-stat-content {
        position: relative;

        z-index: 2;
    }

    .gp-stat-label {
        font-size: 11px;

        font-weight: 700;

        letter-spacing: .7px;

        color: #7c868b;
    }

    .gp-stat-number {
        margin-top: 5px;

        font-size: 32px;

        line-height: 1;

        font-weight: 750;

        color: #20282d;
    }

    .gp-stat-description {
        margin-top: 8px;

        font-size: 12px;

        color: #9aa1a5;
    }

    .gp-stat-icon {
        width: 52px;
        height: 52px;

        min-width: 52px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        font-size: 21px;
    }

    .gp-stat-icon.green {
        background: #e8f6ee;

        color: #16834a;
    }

    .gp-stat-icon.dark-green {
        background: #e8f3ed;

        color: #176b42;
    }

    .gp-stat-icon.gray {
        background: #eef1f2;

        color: #68747b;
    }

    .gp-stat-icon.total {
        background: #eaf1ed;

        color: #2d5c43;
    }


    /* =========================================================
   STATUS CARD
========================================================= */

    .gp-status-card {
        background: #ffffff;

        border: 1px solid #e8ece9;

        border-radius: 14px;

        padding: 20px;

        display: flex;

        align-items: center;

        transition: all .2s ease;
    }

    .gp-status-card:hover {
        transform: translateY(-2px);

        box-shadow: 0 7px 20px rgba(0, 0, 0, .06);
    }

    .gp-status-icon {
        width: 48px;
        height: 48px;

        min-width: 48px;

        border-radius: 12px;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 18px;

        margin-right: 15px;
    }

    .gp-status-icon.active {
        background: #e8f6ee;

        color: #16834a;
    }

    .gp-status-icon.inactive {
        background: #fbecec;

        color: #dc3545;
    }

    .gp-status-icon.document {
        background: #edf4ef;

        color: #3b7657;
    }

    .gp-status-info span {
        display: block;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .6px;

        color: #858e93;
    }

    .gp-status-info strong {
        display: block;

        margin-top: 3px;

        font-size: 25px;

        line-height: 1.1;

        color: #273239;
    }

    .gp-status-info small {
        display: block;

        margin-top: 3px;

        font-size: 11px;

        color: #9aa1a5;
    }


    /* =========================================================
   DOCUMENT CARD
========================================================= */

    .gp-document-card {
        background: #ffffff;

        border: 1px solid #e8ece9;

        border-radius: 14px;

        padding: 22px;

        transition: all .2s ease;
    }

    .gp-document-card:hover {
        transform: translateY(-2px);

        box-shadow: 0 7px 20px rgba(0, 0, 0, .06);
    }

    .gp-document-card.expired {
        border-left: 4px solid #dc3545;
    }

    .gp-document-card.warning {
        border-left: 4px solid #e0a800;
    }

    .gp-document-top {
        display: flex;

        align-items: center;
    }

    .gp-document-icon {
        width: 50px;
        height: 50px;

        min-width: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 13px;

        margin-right: 15px;

        font-size: 19px;
    }

    .expired .gp-document-icon {
        background: #fbecec;

        color: #dc3545;
    }

    .warning .gp-document-icon {
        background: #fff6df;

        color: #d49a00;
    }

    .gp-document-label {
        display: block;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: .6px;

        color: #7b858b;
    }

    .gp-document-number {
        margin-top: 2px;

        font-size: 29px;

        line-height: 1;

        font-weight: 750;

        color: #252d32;
    }

    .gp-document-card p {
        margin: 17px 0;

        font-size: 12px;

        line-height: 1.6;

        color: #858e93;
    }

    .gp-document-button {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        padding: 8px 13px;

        border-radius: 8px;

        font-size: 12px;

        font-weight: 600;

        text-decoration: none;

        transition: all .2s ease;
    }

    .gp-document-button.danger {
        color: #dc3545;

        background: #fff1f1;
    }

    .gp-document-button.warning {
        color: #b47d00;

        background: #fff7e5;
    }

    .gp-document-button:hover {
        text-decoration: none;

        opacity: .85;
    }


    /* =========================================================
   RESPONSIVE
========================================================= */

    @media (max-width: 767.98px) {

        .gp-dashboard-header {
            align-items: flex-start;

            flex-direction: column;

            gap: 15px;

            padding: 18px;
        }

        .gp-title {
            font-size: 19px;
        }

        .gp-subtitle {
            font-size: 11px;

            line-height: 1.5;
        }

        .gp-date {
            width: 100%;

            justify-content: center;
        }

        .gp-stat-card {
            min-height: 125px;

            padding: 18px;
        }

        .gp-stat-number {
            font-size: 28px;
        }

        .gp-stat-icon {
            width: 46px;
            height: 46px;

            min-width: 46px;
        }

    }
</style>

@stop