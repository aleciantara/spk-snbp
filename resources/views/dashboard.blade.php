@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <div class="card-body m-0 pb-0 pt-0 w-100">
                    <div class="alert alert-info alert-has-icon p-4 " bis_skin_checked="1">
                        <div class="alert-icon" bis_skin_checked="1"><i class="far fa-lightbulb"></i></div>
                        <div class="alert-body" bis_skin_checked="1">
                            <div class="alert-title" bis_skin_checked="1">Informasi Penting</div>
                            @if (Auth::user()->role == 'siswa')
                            Siswa dianjurkan melakukan ganti password setelah Login
                            @elseif (Auth::user()->role == 'admin')
                            Admin dianjurkan melakukan ganti password sesering mungkin
                            @endif
                        </div>
                    </div>
                </div>
                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>TOTAL SISWA</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalSiswa }}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IPA</h4>
                            </div>
                            <div class="card-body">
                                {{ $siswaCountByPeminatan['MIPA'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IPS</h4>
                            </div>
                            <div class="card-body">
                                {{ $siswaCountByPeminatan['IPS'] ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>TOTAL KUOTA SNBP</h4>
                            </div>
                            <div class="card-body">
                                {{ $kuotaSnbps->sum('kuota') }}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>KUOTA SNBP MIPA</h4>
                            </div>
                            <div class="card-body">
                                {{ $kuotaSnbps->where('peminatan', 'MIPA')->sum('kuota') }}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class=" col-sm-4 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>KUOTA SNBP IPS</h4>
                            </div>
                            <div class="card-body">
                                {{ $kuotaSnbps->where('peminatan', 'IPS')->sum('kuota') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>TIMELINE SNBP 2024</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6>Proses Seleksi Siswa Eligible SNBP</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>Sedang Berlangsung<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Pengumuman Kuota SNBP Sekolah</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>23 Desember 2023<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Pentapan Siwa Eligible</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>3 Januari - 8 Februari 2024<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Registrasi Akun SNPMB Sekolah & Pengisian PDSS</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>9 Januari - 9 Februari 2024<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Registrasi Akun SNPMB Siswa</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>9 Januari - 15 Februari 2024<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Pendaftaran SNBP Siswa</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>14 - 28 Februari 2024<strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>Pengumuman SNBP</h6>
                                        </td>
                                        <td class="text-right">
                                            <div class="text-primary weight-bold"><strong>28 Maret 2024<strong></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
