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
                <h1>SPK Eligible SNBP</h1>
            </div>

            <div class="col-12 col-md-12 col-sm-12" bis_skin_checked="1">
                <div class="card" bis_skin_checked="1">
                    <div class="card-header" bis_skin_checked="1">
                    <h4>Hasil Eligible SNBP</h4>
                    </div>
                    <div class="card-body" bis_skin_checked="1">
                        <div class="empty-state" data-height="400" style="height: 400px;" bis_skin_checked="1">
                        <div class="empty-state-icon" bis_skin_checked="1">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <h2>Kosong?</h2>
                        <p class="lead">
                            Hasil Seleksi Eligible SNBP belum dirilis. Silakan mengecek seluruh data <br>kembali sembari menunggu info lebih lanjut
                        </p>
                        {{-- <a href="#" class="btn btn-primary mt-4">Create new One</a>
                        <a href="#" class="mt-4 bb">Need Help?</a>
                        </div> --}}
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

