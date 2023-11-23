@extends('layouts.app')

@section('title', 'Detail Data Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Siswa</h1>
        </div>
        <div>
            <div class="card">
                <div class="card-header">
                    <h4>Data Siswa</h4>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-body pb-0" style="font-size: 16px">
                    <div class="row m-4">
                        <!-- Display other fields as text -->
                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>NISN:</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <span>{{ $siswa->nisn }}</span>
                            </div>
                        </div>

                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>Nama Lengkap:</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <span>{{ $siswa->nama }}</span>
                            </div>
                        </div>

                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>Kelas:</h6>
                            </div>
                            <div class="form-group col-md-3">
                                <span>{{ $siswa->kelas_10 ?? 'Kelas X -' }}</span>
                            </div>
                            <div class="form-group col-md-3">
                                <span>{{ $siswa->kelas_11 ?? 'Kelas XI -' }}</span>
                            </div>
                            <div class="form-group col-md-3">
                                <span>{{ $siswa->kelas_12 ?? 'Kelas XII -' }}</span>
                            </div>
                        </div>

                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>Peminatan:</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <span>{{ $siswa->peminatan }}</span>
                            </div>
                        </div>

                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>Sikap:</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <span>{{ $siswa->sikap }}</span>
                            </div>
                        </div>

                        <div class="form-row col-md-10">
                            <div class="form-group col-md-2">
                                <h6>SNBP:</h6>
                            </div>
                            <div class="form-group col-md-4">
                                <span>{{ $siswa->snbp }}</span>
                            </div>
                        </div>
                        

                        <!-- Continue displaying other fields as text -->
                    </div>
                </div>
            </div>
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
