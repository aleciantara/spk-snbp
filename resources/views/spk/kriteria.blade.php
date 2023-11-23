@extends('layouts.app')

@section('title', 'prestasi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content" style="min-height: 535px;">
        <section class="section">
            <div class="section-header">
                <h1>Detail Data Kriteria</h1>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h4> Hasil Detail Data Kriteria </h4>
                    </div>

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @elseif (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('spk.index') }}" style="margin-left: 20px; margin-top:10px" method="GET">
                        <div class="row mt-2">
                            <div class="col-md-7">
                                <table class="table-striped mb-0 mr-0 table">
                                    <tbody>
                                        <tr>
                                            @foreach($bobotKriterias as $bobot)
                                                <td>Bobot {{ $bobot->nama_kriteria }}: {{$bobot->bobot*100}}%</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table-striped mb-0 table">
                                    <tbody>
                                        <tr>
                                            @foreach($kuotaSnbps as $kuota)
                                                <td>Kuota {{ $kuota->peminatan }}: {{$kuota->kuota}}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="input-group mt-3">
                            <!-- Search input -->
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Ketik NISN, Nama" value="{{ request('search') }}" oninput="validateSearchInput(this)">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                    
           

                    <div class="card-body p-4">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="mipa-tab" data-toggle="tab" href="#mipa-table">MIPA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ips-tab" data-toggle="tab" href="#ips-table">IPS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- MIPA Table -->
                            <div class="tab-pane fade show active" id="mipa-table">
                                @include('partials.spkKriteriaTable', ['peminatan' => 'MIPA'])
                            </div>

                            <!-- IPS Table -->
                            <div class="tab-pane fade" id="ips-table">
                                @include('partials.spkKriteriaTable', ['peminatan' => 'IPS'])
                            </div>
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

