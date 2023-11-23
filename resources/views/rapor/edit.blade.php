@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section disable-padding no-background-image" >
        <div class="section-header">
            <h1>Data Rapor</h1>
        </div>
        
        <form method="POST" action="{{ route('rapor.update', $siswa->nisn) }}" class="needs-validation" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h4>Form Rapor {{$siswa->nisn}} - {{$siswa->nama}}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('siswa.prestasi', ['nisn' => $siswa->nisn]) }}" class="btn btn-info mr-2">Prestasi Siswa</a>
                        <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-warning">Detail Siswa</a>
                    </div>
                </div>

                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

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

                        
                        <div class="row col-sm-12">
                            <label for="pelajaran" class="col-sm-4 col-form-label">Mata Pelajaran</label>
                            <label for="semester_1" class="col-sm-1 mr-2 ml-3 text-center col-form-label">SMT 1</label>
                            <label for="semester_2" class="col-sm-1 mr-2 ml-3 text-center col-form-label">SMT 2</label>
                            <label for="semester_3" class="col-sm-1 mr-2 ml-3 text-center col-form-label">SMT 3</label>
                            <label for="semester_4" class="col-sm-1 mr-2 ml-3 text-center col-form-label">SMT 4</label>
                            <label for="semester_5" class="col-sm-1 mr-2 ml-3 text-center col-form-label">SMT 5</label>
                            <label for="rata_rata" class="col-sm-1 mr-3 ml-2 text-center col-form-label">Rata2</label>
                        </div>


                        @foreach ($raporData as $index => $rapor)
                        <div class="container p-0">
                            <div class="form-group row col-sm-12">
                                <input style="width: 280px; height: 27px; margin-right:56px" type="text" name="pelajaran[]" id="pelajaran" class="form-control-sm form-control" value="{{ old('pelajaran.' . $index, $rapor->pelajaran) }}" placeholder="{{ $rapor->pelajaran }}" readonly>

                                @for ($i = 1; $i <= 5; $i++)
                                <input style="width: 43px; height: 27px; margin-right:56px;" type="text" name="sem_{{ $i }}_nilai_p[]" class="form-control-sm form-control ml-6" placeholder="" value="{{ old("sem_${i}_nilai_p.${index}", $rapor["sem_${i}_nilai_p"]) }}">
                                @endfor

                                <input style="width: 43px; height: 27px;" type="text" name="rata_nilai_p[]" class="form-control-sm form-control ml-6" placeholder="{{ old("rata_nilai_p.${index}", $rapor["rata_nilai_p"]) }}" readonly>
                                
                            </div>
                        </div>
                        @endforeach
                        <div class="container p-0">
                            <div class="form-group row col-sm-12">
                                <input style="width: 280px; height: 27px; margin-right:56px" type="text"  class="form-control-sm form-control"  value="Rata-Rata Rapor" readonly>

                                <input style="width: 43px; height: 27px; margin-left:496px" type="text" class="form-control-sm form-control ml-6" value="{{$spkKriteria->rapor}}" readonly>
                                
                            </div>
                        </div>



                    </div>
                </div>

                <div class="card-footer pt-0">
                    <button type="submit" class="btn btn-lg btn-primary float-right">Save</button>
                </div>
            </div>
        </form>
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

.no-background-image .form-control:valid {
  background-image: none !important; /* Disable the background image */
  padding-right: 0 !important; 
  background-position: 0 !important;
  background-size: 0 !important;
}

.no-background-image .form-control:invalid {
  background-image: none !important;
  padding-right: 0 !important; 
  background-position: 0  !important;
  background-size: 0 !important;
}

.disable-padding .form-control.is-invalid,
.disable-padding .was-validated .form-control:invalid {
  padding-right: 0 !important; /* Remove padding-right */
}

.disable-padding .form-control.is-valid,
.disable-padding .was-validated .form-control:valid {
  padding-right: 0 !important; /* Remove padding-right */
}

</style>
