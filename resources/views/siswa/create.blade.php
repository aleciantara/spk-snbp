@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Siswa</h1>
        </div>
        <div>
            <form method="POST" action="{{ route('siswa.store') }}" class="needs-validation" enctype="multipart/form-data" novalidate="">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Form Data Siswa</h4>
                    </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card-body pb-0">
                            <div class="row m-4">
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="nisn">NISN</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" name="nisn" id="nisn" oninput="validateNISN(this)" class="form-control{{ $errors->has('nisn') ? ' is-invalid' : '' }}" value="{{ old('nisn') }}" required>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="nama">Nama Lengkap</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="nama" id="nama" oninput="validateNama(this)" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" value="{{ old('nama') }}" required>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="kelas">Kelas</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="kelas_10" id="kelas_10" class="form-control{{ $errors->has('kelas_10') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Kelas X</option>
                                            <option value="Pindahan" {{ old('kelas_10') == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
                                            <option value="X - MIPA 1" {{ old('kelas_10') == 'X - MIPA 1' ? 'selected' : '' }}>X - MIPA 1</option>
                                            <option value="X - MIPA 2" {{ old('kelas_10') == 'X - MIPA 2' ? 'selected' : '' }}>X - MIPA 2</option>
                                            <option value="X - MIPA 3" {{ old('kelas_10') == 'X - MIPA 3' ? 'selected' : '' }}>X - MIPA 3</option>
                                            <option value="X - MIPA 4" {{ old('kelas_10') == 'X - MIPA 4' ? 'selected' : '' }}>X - MIPA 4</option>
                                            <option value="X - MIPA 5" {{ old('kelas_10') == 'X - MIPA 5' ? 'selected' : '' }}>X - MIPA 5</option>
                                            <option value="X - MIPA 6" {{ old('kelas_10') == 'X - MIPA 6' ? 'selected' : '' }}>X - MIPA 6</option>
                                            <option value="X - MIPA 7" {{ old('kelas_10') == 'X - MIPA 7' ? 'selected' : '' }}>X - MIPA 7</option>
                                            <option value="X - IPS 8" {{ old('kelas_10') == 'X - IPS 8' ? 'selected' : '' }}>X - IPS 8</option>
                                            <option value="X - IPS 9" {{ old('kelas_10') == 'X - IPS 9' ? 'selected' : '' }}>X - IPS 9</option>
                                            <option value="X - IPS 10" {{ old('kelas_10') == 'X - IPS 10' ? 'selected' : '' }}>X - IPS 10</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="kelas_11" id="kelas_11" class="form-control{{ $errors->has('kelas_11') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Kelas XI</option>
                                            <option value="Pindahan" {{ old('kelas_11') == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
                                            <option value="XI - MIPA 1" {{ old('kelas_11') == 'XI - MIPA 1' ? 'selected' : '' }}>XI - MIPA 1</option>
                                            <option value="XI - MIPA 2" {{ old('kelas_11') == 'XI - MIPA 2' ? 'selected' : '' }}>XI - MIPA 2</option>
                                            <option value="XI - MIPA 3" {{ old('kelas_11') == 'XI - MIPA 3' ? 'selected' : '' }}>XI - MIPA 3</option>
                                            <option value="XI - MIPA 4" {{ old('kelas_11') == 'XI - MIPA 4' ? 'selected' : '' }}>XI - MIPA 4</option>
                                            <option value="XI - MIPA 5" {{ old('kelas_11') == 'XI - MIPA 5' ? 'selected' : '' }}>XI - MIPA 5</option>
                                            <option value="XI - MIPA 6" {{ old('kelas_11') == 'XI - MIPA 6' ? 'selected' : '' }}>XI - MIPA 6</option>
                                            <option value="XI - MIPA 7" {{ old('kelas_11') == 'XI - MIPA 7' ? 'selected' : '' }}>XI - MIPA 7</option>
                                            <option value="XI - IPS 8" {{ old('kelas_11') == 'XI - IPS 8' ? 'selected' : '' }}>XI - IPS 8</option>
                                            <option value="XI - IPS 9" {{ old('kelas_11') == 'XI - IPS 9' ? 'selected' : '' }}>XI - IPS 9</option>
                                            <option value="XI - IPS 10" {{ old('kelas_11') == 'XI - IPS 10' ? 'selected' : '' }}>XI - IPS 10</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="kelas_12" id="kelas_12" class="form-control{{ $errors->has('kelas_12') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Kelas XII</option>
                                            <option value="XII - MIPA 1" {{ old('kelas_12') == 'XII - MIPA 1' ? 'selected' : '' }}>XII - MIPA 1</option>
                                            <option value="XII - MIPA 2" {{ old('kelas_12') == 'XII - MIPA 2' ? 'selected' : '' }}>XII - MIPA 2</option>
                                            <option value="XII - MIPA 3" {{ old('kelas_12') == 'XII - MIPA 3' ? 'selected' : '' }}>XII - MIPA 3</option>
                                            <option value="XII - MIPA 4" {{ old('kelas_12') == 'XII - MIPA 4' ? 'selected' : '' }}>XII - MIPA 4</option>
                                            <option value="XII - MIPA 5" {{ old('kelas_12') == 'XII - MIPA 5' ? 'selected' : '' }}>XII - MIPA 5</option>
                                            <option value="XII - MIPA 6" {{ old('kelas_12') == 'XII - MIPA 6' ? 'selected' : '' }}>XII - MIPA 6</option>
                                            <option value="XII - MIPA 7" {{ old('kelas_12') == 'XII - MIPA 7' ? 'selected' : '' }}>XII - MIPA 7</option>
                                            <option value="XII - IPS 8" {{ old('kelas_12') == 'XII - IPS 8' ? 'selected' : '' }}>XII - IPS 8</option>
                                            <option value="XII - IPS 9" {{ old('kelas_12') == 'XII - IPS 9' ? 'selected' : '' }}>XII - IPS 9</option>
                                            <option value="XII - IPS 10" {{ old('kelas_12') == 'XII - IPS 10' ? 'selected' : '' }}>XII - IPS 10</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="kelas">Peminatan</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="peminatan" id="peminatan" class="form-control{{ $errors->has('peminatan') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Peminatan</option>
                                            <option value="MIPA" {{ old('peminatan') == 'MIPA' ? 'selected' : '' }}>MIPA</option>
                                            <option value="IPS" {{ old('peminatan') == 'IPS' ? 'selected' : '' }}>IPS</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="kelas">Nilai Sikap</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="sikap" id="sikap" class="form-control{{ $errors->has('sikap') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Sikap</option>
                                            <option value="Sangat Baik" {{ old('sikap') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                            <option value="Baik" {{ old('sikap') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="Cukup" {{ old('sikap') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                            <option value="Buruk" {{ old('sikap') == 'Buruk' ? 'selected' : '' }}>Buruk</option>
                                            <option value="Sangat Buruk" {{ old('sikap') == 'Sangat Buruk' ? 'selected' : '' }}>Sangat Buruk</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="snbp">SNBP</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="snbp" id="snbp" class="form-control{{ $errors->has('snbp') ? ' is-invalid' : '' }}" required>
                                            <option value="Bersedia" {{ old('snbp') == 'Bersedia' ? 'selected' : '' }} selected>Bersedia</option>
                                            <option value="Tidak Bersedia" {{ old('snbp') == 'Tidak Bersedia' ? 'selected' : '' }}>Tidak Bersedia</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        
                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-lg btn-primary float-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    <script>
        function validateNama(input) {
            input.value = input.value.replace(/[^a-zA-Z0-9-: ]/g, '');
        }

        function validateNISN(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
@endpush

<style>
    .form-label {
        display: flex;
        align-items: center;
        justify-content: left;
        height: 100%;
        font-size: 16px;
    }

    .hapus-baris {
        background-color: transparent;
        border: none;
        color: red;
        cursor: pointer;
        font-size: 20px;
        padding: 0; /* Menghilangkan padding agar menjadi bulat */
        margin-left: -30px;
        margin-bottom: -14px; /* Menempelkan tombol ke sisi kiri */
        position: absolute; /* Menjadikan tombol sebagai posisi absolut */
    }

</style>






