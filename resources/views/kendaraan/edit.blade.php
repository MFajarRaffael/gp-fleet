@extends('adminlte::page')

@section('title', 'Edit Unit')

@section('css')

<link rel="stylesheet" href="{{ asset('css/kendaraan.css') }}">
@stop

@section('content_header')

<div class="gp-page-header">

    
    <div class="gp-page-header-left">

        <div class="gp-page-icon">
            <i class="fas fa-edit"></i>
        </div>

        <div>
            <h1>Edit Unit</h1>
            <p>Perbarui informasi unit kendaraan GP Fleet Management System</p>
        </div>

    </div>
    

</div>

@stop

@section('content')

@if ($errors->any())

    <div class="gp-form-alert">

        
        <div class="gp-form-alert-icon">
            <i class="fas fa-circle-exclamation"></i>
        </div>

        <div>

            <strong>Data belum bisa diperbarui.</strong>

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
                <i class="fas fa-car-side"></i>
            </div>

            <div>

                <h5>Edit Data Unit</h5>

                <span>
                    Nomor Unit : {{ $kendaraan->nomor_unit }}
                </span>

            </div>

        </div>

    </div>


    <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="gp-form-body">


            {{-- ======================== --}}
            {{-- INFORMASI UNIT --}}
            {{-- ======================== --}}

            <div class="gp-form-section">

                <div class="gp-form-section-title">
                    <i class="fas fa-car"></i>
                    Informasi Unit
                </div>

                {{-- FOTO --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-image mr-1"></i>
                        Foto Unit
                    </label>

                    @if($kendaraan->foto)

                        <div class="mb-3">

                            <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="gp-vehicle-photo"
                                style="width:170px;height:120px;">

                        </div>

                    @endif

                    <input type="file" name="foto" class="gp-input-file" accept="image/jpeg,image/png,image/webp">

                    <small>
                        Upload foto baru jika ingin mengganti foto lama.
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

                        <option value="">-- Pilih Kategori --</option>

                        <option value="Kendaraan" {{ old('kategori', $kendaraan->kategori) == 'Kendaraan' ? 'selected' : '' }}>
                            Kendaraan
                        </option>

                        <option value="HV" {{ old('kategori', $kendaraan->kategori) == 'HV' ? 'selected' : '' }}>
                            HV
                        </option>

                        <option value="HE" {{ old('kategori', $kendaraan->kategori) == 'HE' ? 'selected' : '' }}>
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
                            -- Pilih Tipe --
                        </option>

                    </select>

                </div>


                {{-- PLAT NOMOR --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-id-card mr-1"></i>
                        Plat Nomor
                    </label>

                    <input type="text" name="plat_nomor" class="gp-input"
                        value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" placeholder="BM 1234 AB">

                </div>


                {{-- NOMOR UNIT --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-hashtag mr-1"></i>
                        Nomor Unit
                        <span class="required">*</span>
                    </label>

                    <input type="text" name="nomor_unit" class="gp-input"
                        value="{{ old('nomor_unit', $kendaraan->nomor_unit) }}" required>

                </div>


                {{-- MERK --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-industry mr-1"></i>
                        Merk
                        <span class="required">*</span>
                    </label>

                    <input type="text" name="merk" class="gp-input" value="{{ old('merk', $kendaraan->merk) }}" required>

                </div>

            </div>


            {{-- ======================== --}}
            {{-- PENGELOLAAN UNIT --}}
            {{-- ======================== --}}

            <div class="gp-form-section">

                <div class="gp-form-section-title">
                    <i class="fas fa-users"></i>
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
                            -- Tidak Ada PIC --
                        </option>

                        @foreach($pics as $pic)

                            <option value="{{ $pic->id }}" {{ old('pic_id', $kendaraan->pic_id) == $pic->id ? 'selected' : '' }}>

                                {{ $pic->nama }}

                                @if($pic->jabatan)
                                    - {{ $pic->jabatan }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    <small>
                        PIC bertanggung jawab terhadap unit ini.
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
                        value="{{ old('tahun', $kendaraan->tahun) }}" required>

                </div>


                {{-- PROJECT --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-diagram-project mr-1"></i>
                        Project
                    </label>

                    <input type="text" name="project" class="gp-input" value="{{ old('project', $kendaraan->project) }}"
                        placeholder="Nama Project">

                </div>


                {{-- STATUS --}}

                <div class="gp-form-group">

                    <label>
                        <i class="fas fa-toggle-on mr-1"></i>
                        Status Unit
                        <span class="required">*</span>
                    </label>

                    <select name="status" class="gp-input" required>

                        <option value="Aktif" {{ old('status', $kendaraan->status) == 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="Tidak Aktif" {{ old('status', $kendaraan->status) == 'Tidak Aktif' ? 'selected' : '' }}>
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

                <i class="fas fa-floppy-disk"></i>
                Update Unit

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

    function updateTipe(selected = null) {

        const kategoriDipilih = kategori.value;

        tipe.innerHTML = '<option value="">-- Pilih Tipe --</option>';

        if (!kategoriDipilih) return;

        tipeData[kategoriDipilih].forEach(function (item) {

            const option = document.createElement('option');

            option.value = item;
            option.textContent = item;

            if (selected === item) {
                option.selected = true;
            }

            tipe.appendChild(option);

        });

    }

    kategori.addEventListener('change', () => updateTipe());

    document.addEventListener('DOMContentLoaded', function () {

        const oldKategori = "{{ old('kategori', $kendaraan->kategori) }}";
        const oldTipe = "{{ old('tipe', $kendaraan->tipe) }}";

        kategori.value = oldKategori;

        updateTipe(oldTipe);

    });

</script>

@stop