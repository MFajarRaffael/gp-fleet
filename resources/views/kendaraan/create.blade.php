@extends('adminlte::page')

@section('title', 'Tambah Unit')

@section('css')
<link rel="stylesheet" href="{{ asset('css/kendaraan.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    
    <div class="gp-page-header-left">
<!-- 
        <div class="gp-page-icon">
            <i class="fas fa-car"></i>
        </div>

        <div>
            <h1>Tambah Unit</h1>
            <p>Tambahkan data unit kendaraan baru ke sistem GP Fleet</p>
        </div> -->

    </div>
    

</div>

@stop

@section('content')

@if ($errors->any())

    
    <div class="gp-form-alert">

        <div class="gp-form-alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <div>

            <strong>Terjadi kesalahan!</strong>

            <ul>
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

            <div class="gp-form-icon">
                <i class="fas fa-car"></i>
            </div>

            <div>

                <h5>Form Tambah Unit</h5>

                <span>
                    Lengkapi informasi unit kendaraan
                </span>

            </div>

        </div>

    </div>


    {{-- FORM --}}

    <form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="gp-form-body">


            {{-- ============================= --}}
            {{-- INFORMASI UNIT --}}
            {{-- ============================= --}}

            <div class="gp-form-section">

                <div class="gp-form-section-title">

                    <i class="fas fa-car-side"></i>

                    Informasi Unit

                </div>


                {{-- FOTO --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-image mr-1"></i>
                        Foto Unit
                    </label>

                    <input type="file" name="foto" class="gp-input-file" accept="image/jpeg,image/png,image/webp">

                    <small>
                        Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                    </small>

                </div>


                {{-- KATEGORI --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-layer-group mr-1"></i>
                        Kategori
                        <span class="required">*</span>
                    </label>

                    <select name="kategori" id="kategori" class="gp-input" required>

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <option value="Kendaraan" {{ old('kategori') == 'Kendaraan' ? 'selected' : '' }}>
                            Kendaraan
                        </option>

                        <option value="HV" {{ old('kategori') == 'HV' ? 'selected' : '' }}>
                            HV
                        </option>

                        <option value="HE" {{ old('kategori') == 'HE' ? 'selected' : '' }}>
                            HE
                        </option>

                    </select>

                </div>


                {{-- TIPE --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-truck mr-1"></i>
                        Tipe Unit
                        <span class="required">*</span>
                    </label>

                    <select name="tipe" id="tipe" class="gp-input" required>

                        <option value="">
                            -- Pilih Kategori Terlebih Dahulu --
                        </option>

                    </select>

                </div>


                {{-- PLAT NOMOR --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-id-card mr-1"></i>
                        Plat Nomor
                    </label>

                    <input type="text" name="plat_nomor" class="gp-input" value="{{ old('plat_nomor') }}"
                        placeholder="Contoh: BM 1234 AB">

                </div>


                {{-- NOMOR UNIT --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-hashtag mr-1"></i>
                        Nomor Unit
                        <span class="required">*</span>
                    </label>

                    <input type="text" name="nomor_unit" class="gp-input" value="{{ old('nomor_unit') }}"
                        placeholder="Contoh: UNIT-001" required>

                </div>


                {{-- MERK --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-car-side mr-1"></i>
                        Merk
                        <span class="required">*</span>
                    </label>

                    <input type="text" name="merk" class="gp-input" value="{{ old('merk') }}"
                        placeholder="Contoh: Toyota" required>

                </div>

            </div>


            {{-- ============================= --}}
            {{-- PENGELOLAAN UNIT --}}
            {{-- ============================= --}}

            <div class="gp-form-section">

                <div class="gp-form-section-title">

                    <i class="fas fa-users-cog"></i>

                    Pengelolaan Unit

                </div>


                {{-- PIC --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-user-tie mr-1"></i>
                        PIC
                    </label>

                    <select name="pic_id" class="gp-input">

                        <option value="">
                            -- Pilih PIC --
                        </option>

                        @foreach($pics as $pic)

                            <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>

                                {{ $pic->nama }}

                                @if($pic->jabatan)
                                    - {{ $pic->jabatan }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    <small>
                        Satu PIC dapat menangani beberapa kendaraan.
                    </small>

                </div>


                {{-- TAHUN --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-calendar mr-1"></i>
                        Tahun
                        <span class="required">*</span>
                    </label>

                    <input type="number" name="tahun" class="gp-input" min="1900" max="{{ date('Y') + 1 }}"
                        value="{{ old('tahun') }}" placeholder="Contoh: 2022" required>

                </div>


                {{-- PROJECT --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-project-diagram mr-1"></i>
                        Project
                    </label>

                    <input type="text" name="project" class="gp-input" value="{{ old('project') }}"
                        placeholder="Nama project">

                </div>


                {{-- STATUS --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-toggle-on mr-1"></i>
                        Status
                        <span class="required">*</span>
                    </label>

                    <select name="status" class="gp-input" required>

                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}

        <div class="gp-form-footer">

            <a href="{{ route('kendaraan.index') }}" class="gp-btn gp-btn-light">

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>


            <button type="submit" class="gp-btn gp-btn-primary">

                <i class="fas fa-save"></i>

                Simpan Unit

            </button>

        </div>

    </form>
    

</div>

@stop

@section('js')

<script>

    const tipeData = {

        Kendaraan: [
            'Double Cabin',
            'Minibus',
            'Single Cabin',
            'Pickup',
            'Dumptruck',
            'Truck Compactor',
            'Pick Up Single Cabin'
        ],

        HV: [
            'Vacuum Truck',
            'Truk Tangki Vacuum',
            'Dumptruck',
            'Truck Compactor',
            'Garbage Truck',
            'Truck Barang'
        ],

        HE: [
            'Backhoe Loader',
            'Bulldozer',
            'Bulldozer Komatsu D85',
            'Excavator',
            'Traktor',
            'Bulldozer D65P-12'
        ]

    };


    const kategori = document.getElementById('kategori');
    const tipe = document.getElementById('tipe');


    function updateTipe() {

        const selectedKategori = kategori.value;

        tipe.innerHTML = '';


        if (!selectedKategori) {

            tipe.innerHTML = `
                <option value="">
                    -- Pilih Kategori Terlebih Dahulu --
                </option>
            `;

            return;
        }


        tipe.innerHTML = `
            <option value="">
                -- Pilih Tipe --
            </option>
        `;


        tipeData[selectedKategori].forEach(function (item) {

            const option = document.createElement('option');

            option.value = item;
            option.textContent = item;

            tipe.appendChild(option);

        });

    }


    kategori.addEventListener('change', updateTipe);


    document.addEventListener('DOMContentLoaded', function () {

        const oldKategori = "{{ old('kategori') }}";
        const oldTipe = "{{ old('tipe') }}";


        if (oldKategori) {

            kategori.value = oldKategori;

            updateTipe();


            if (oldTipe) {
                tipe.value = oldTipe;
            }

        }

    });

</script>

@stop