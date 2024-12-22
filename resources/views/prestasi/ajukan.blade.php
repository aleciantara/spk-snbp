@extends('layouts.app')

@section('title', 'Tambah Data Prestasi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section">
        <div class="section-header">
            <h1>Ajukan Prestasi</h1>
        </div>
        <div>
            <form method="POST" action="{{ route('prestasi.storePengajuanPrestasi') }}" class="needs-validation" enctype="multipart/form-data" novalidate="">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Form Pengajuan Prestasi</h4>
                        <div class="card-header-action">
                            <a class="btn btn-primary" data-toggle="modal" data-target="#poinPrestasiModal">Panduan Poin Prestasi</a>
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

                        <div class="card-body pb-0">
                            <div class="row m-4">

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="judul">Nama Siswa</label>
                                    </div>

                                    
                                    <div class="form-group col-md-8">
                                        @if ($siswa)
                                        <div style="margin-bottom:10px">
                                            <input type="text" class="form-control" value="{{ $siswa->nisn }} - {{ $siswa->nama }}" readonly>
                                            <input type="hidden" name="nama[]" value="{{ $siswa->nisn }}">
                                            </div>
                                        @elseif (!$siswa)
                                        <div style="margin-bottom:10px"id="baris-0">
                                            <select  class="form-control select2" name="nama[]" id="nama_0" required>
                                                <option ></option>
                                                @foreach ($siswas as $siswa)
                                                    <option value="{{ $siswa->nisn }}" {{ old('nama_0') == $siswa->nisn  ? 'selected' : '' }}>{{ $siswa->nisn }} - {{ $siswa->nama }}</option>
                                                @endforeach
                                                
                                            </select>                                            
                                        </div>
                                        
                                        @endif

                                        
                                        <div  id="NamaSiswaBaru">
                                            <!-- Baris baru akan ditambahkan di sini -->
                                        </div>
            
                                        <button type="button" id="tambahNamaSiswa" class="btn btn-light">
                                                + Nama Siswa
                                        </button>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="prestasi">Judul Prestasi</label>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <input type="text" name="prestasi" id="prestasi" placeholder="Judul Prestasi" oninput="validatePrestasiAndPenyelenggara(this)" class="form-control{{ $errors->has('prestasi') ? ' is-invalid' : '' }}" value="{{ old('prestasi') }}" required>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="penyelenggara">Penyelenggara</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="text" name="penyelenggara" id="penyelenggara" placeholder="Penyelenggara" oninput="validatePrestasiAndPenyelenggara(this)" class="form-control{{ $errors->has('penyelenggara') ? ' is-invalid' : '' }}" value="{{ old('penyelenggara') }}" required>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="juara">Juara</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="juara" id="juara" class="form-control{{ $errors->has('juara') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Juara</option>
                                            <option value="Juara 1" {{ old('juara') == 'Juara 1' ? 'selected' : '' }}>Juara 1</option>
                                            <option value="Juara 2" {{ old('juara') == 'Juara 2' ? 'selected' : '' }}>Juara 2</option>
                                            <option value="Juara 3" {{ old('juara') == 'Juara 3' ? 'selected' : '' }}>Juara 3</option>
                                            <option value="Juara Harapan 1" {{ old('juara') == 'Juara Harapan 1' ? 'selected' : '' }}>Juara Harapan 1</option>
                                            <option value="Juara Harapan 2" {{ old('juara') == 'Juara Harapan 2' ? 'selected' : '' }}>Juara Harapan 2</option>
                                            <option value="Juara Harapan 3" {{ old('juara') == 'Juara Harapan 3' ? 'selected' : '' }}>Juara Harapan 3</option>
                                            <option value="Juara Favorit" {{ old('juara') == 'Juara Favorit' ? 'selected' : '' }}>Juara Favorit</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="tingkat">Tingkat</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="tingkat" id="tingkat" class="form-control{{ $errors->has('tingkat') ? ' is-invalid' : '' }}" required>
                                            <option value="">Pilih Tingkat</option>
                                            <option value="Sekolah" {{ old('tingkat') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                            <option value="Kabupaten/Kota" {{ old('tingkat') == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                            <option value="Provinsi" {{ old('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                            <option value="Nasional" {{ old('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                            <option value="Internasional" {{ old('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose an option
                                        </div>
                                    </div>
                                </div> 

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="waktu">Tgl Lomba</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input type="date" class="form-control" id="waktu" name="waktu" {{ old('waktu') }}>
                                    </div>
                                </div>

                                

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="poin">Sertifikat/Piala</label>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <div class="custom-file" bis_skin_checked="1">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept=".png, .jpeg, .pdf" required>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
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

<div class="modal fade" id="poinPrestasiModal" tabindex="-1" role="dialog" aria-labelledby="poinPrestasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="poinPrestasiModalLabel">Panduan Poin Prestasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mb-0 pb-0">
                <div class="form-group">
                    <img src="{{ asset('panduan-poin-prestasi.jpg') }}" alt="Panduan Poin Prestasi" class="img-fluid">
                </div>
            </div>
            
        </div>
    </div>    
</div>

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    <script>
        function validatePrestasiAndPenyelenggara(input) {
            input.value = input.value.replace(/[^a-zA-Z0-9-: ]/g, '');
        }

        function validatePoin(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>

    <script>    
            document.addEventListener("DOMContentLoaded", function () {
        // Initialize Select2 for the initial nama field
        $('.select2').select2({
            placeholder: 'Pilih Nama Siswa',
        });

        const addButton = document.querySelector("#tambahNamaSiswa");
        const container = document.querySelector("#NamaSiswaBaru");
        let index = 1;

        addButton.addEventListener("click", function () {
            const newRow = `
            <div style="margin-bottom:10px" id="baris-${index}" class="position-relative">
                <select class="form-control select2" name="nama[]" id="nama_${index}" required>
                    <option></option>
                    @foreach ($siswas as $siswa)
                        <option value="{{ $siswa->nisn }}" {{ old('nama_${index}') == $siswa->nisn ? 'selected' : '' }}>{{ $siswa->nisn }} - {{ $siswa->nama }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger btn-sm hapus-baris rounded-circle" data-index="${index}">x</button>
            </div>
            `;
            container.insertAdjacentHTML("beforeend", newRow);
            index++;

            // Initialize Select2 for the newly added nama field
            $(`#nama_${index - 1}`).select2({
                placeholder: 'Pilih Nama Siswa',
            });
        });

        // Tambahkan event listener untuk tombol "Hapus"
        container.addEventListener("click", function (event) {
            if (event.target.classList.contains("hapus-baris")) {
                const rowIndex = event.target.getAttribute("data-index");
                const rowToRemove = document.querySelector(`#baris-${rowIndex}`);
                if (rowToRemove) {
                    rowToRemove.remove();
                }
            }
        });
    });

    </script>

<script>
    const fileInput = document.getElementById('file');
    const customFileLabel = document.querySelector('.custom-file-label');

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            customFileLabel.textContent = fileInput.files[0].name;
        } else {
            customFileLabel.textContent = 'Choose file';
        }
    });
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
    background-color: red;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 20px;
    padding: 10px;
    margin-left: 10px;
    margin-top: -5px;
    position: absolute; /* Menjadikan tombol sebagai posisi absolut */
    left: 580px; /* Adjust this value to move the button to the left */
    top: 50%;
    transform: translateY(-50%);
    }

    .position-relative {
        position: relative;
    }

</style>






