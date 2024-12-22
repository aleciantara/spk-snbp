@extends('layouts.app')

@section('title', 'Edit Surat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section">
        <div class="section-header">
            <h1>Edit Surat</h1>
        </div>
        <div>
            <form method="POST" action="{{ route('surat.updateSurat', ['id' => $surat->id]) }}" class="needs-validation" enctype="multipart/form-data" novalidate="">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Surat</h4>
                        <div class="card-header-action">
                            <a href="{{ route('siswa.edit', ['nisn' => $surat->siswa->nisn]) }}" class="btn btn-warning mr-2">Data Siswa</a>
                            <a href="{{ route('rapor.edit', ['nisn' => $surat->siswa->nisn]) }}" class="btn btn-primary">Rapor Siswa</a>
                            <a href="{{ route('siswa.prestasi', ['nisn' => $surat->siswa->nisn]) }}" class="btn btn-info mr-2">Prestasi Siswa</a>
                        </div>
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
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="card-body pb-0">
                            <div class="row m-4">
                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="nisn">NISN</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" name="nisn" id="nisn" class="form-control{{ $errors->has('nisn') ? ' is-invalid' : '' }}" value="{{ $surat->siswa->nisn }}" readonly>
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
                                        <input type="text" name="nama" id="nama" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" value="{{ $surat->siswa->nama }}" readonly>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="nama">Judul Surat</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="judul" id="judul" class="form-control{{ $errors->has('judul') ? ' is-invalid' : '' }}" value="{{ $surat->judul }}" readonly>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="nama">Catatan</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <textarea style="min-height:180px" name="catatan" id="catatan" class="form-control{{ $errors->has('catatan') ? ' is-invalid' : '' }}" required>{{ $surat->catatan }}</textarea>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="status">Status</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="status" id="status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" required>
                                            <option value="{{ $surat->status }}">{{ $surat->status }}</option>
                                            <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                            <option value="unverified" {{ old('status') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                                            <option value="denied" {{ old('tingkat') == 'denied' ? 'selected' : '' }}>Denied</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="file">File</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <a href="{{ route('surat.downloadSurat', ['surat' => $surat]) }}" target="_blank" class="btn btn-md mb-1 btn-primary fancybox" data-fancybox-type="ajax">Download File <i class="fas fa-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                                
                        <div class="card-footer pt-0">
                            <button type="submit" class="btn btn-lg btn-primary float-right">Save</button>
                            <a href="{{ route('siswa.mundurSnbp', ['nisn' => $surat->siswa->nisn]) }}" class="btn btn-lg btn-danger float-right mr-2">Mundur dari SNBP</a>
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






