@extends('layouts.app')

@section('title', 'Edit Data Prestasi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('main')
<div class="main-content" style="min-height: 535px;">
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Prestasi</h1>
        </div>
        <div>
            <form method="POST" action="{{ route('prestasi.update', ['id' => $prestasi->id]) }}" class="needs-validation" enctype="multipart/form-data" novalidate="">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h4>Form Edit Data Prestasi</h4>
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

                                    
                                    <div class="form-group col-md-8 " id="namaFields">
                                        @foreach ($prestasi->siswas as $index => $siswa)
                                            <div style="margin-bottom: 10px" id="baris-{{ $index }}">
                                                <select class="form-control select2" name="nama[]" id="nama_{{ $index }}" required>
                                                    <option value="{{ $siswa->nisn }}" {{ old('nama_' . $index) == $siswa->nisn ? 'selected' : '' }}>
                                                        {{ $siswa->nisn }} - {{ $siswa->nama }}
                                                    </option>
                                                    @foreach ($siswas as $availableSiswa)
                                                        <option value="{{ $availableSiswa->nisn }}" {{ old('nama_' . $index) == $availableSiswa->nisn ? 'selected' : '' }}>
                                                            {{ $availableSiswa->nisn }} - {{ $availableSiswa->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-danger btn-sm hapus-baris rounded-circle" data-index="{{$index}}">x</button>
                                            </div>
                                        @endforeach

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
                                        <input type="text" name="prestasi" id="prestasi" oninput="validatePrestasiAndPenyelenggara(this)" class="form-control{{ $errors->has('prestasi') ? ' is-invalid' : '' }}" value="{{ $prestasi->prestasi }}" required>
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
                                        <input type="text" name="penyelenggara" id="penyelenggara" oninput="validatePrestasiAndPenyelenggara(this)" class="form-control{{ $errors->has('penyelenggara') ? ' is-invalid' : '' }}" value="{{ $prestasi->penyelenggara }}" required>
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
                                            <option value="{{ $prestasi->juara }}" selected>{{ $prestasi->juara }}</option>
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
                                            <option value="{{ $prestasi->tingkat }}">{{ $prestasi->tingkat }}</option>
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
                                        <input type="date" class="form-control" id="waktu" name="waktu" value="{{ date('Y-m-d', strtotime($prestasi->waktu)) }}">
                                    </div>
                                </div>                          
                                

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="poin">Poin</label>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <input type="number" class="form-control" oninput="validatePoin(this)" id="poin" name="poin" value="{{ $prestasi->poin }}" step="10">
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="status">Status</label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select name="status" id="status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" required>
                                            <option value="{{ $prestasi->status }}">{{ $prestasi->status }}</option>
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
                                        <label class="form-label" for="gambar">Gambar</label>
                                    </div>
                                    <div class="form-group col-md-6 " >
                                        @if ($prestasi->file)  
                                            <a href="{{ asset('uploads/' . $prestasi->file) }}" data-fancybox data-caption="{{ $prestasi->file }}">
                                                <img src="{{ asset('uploads/' . $prestasi->file) }}" width="200px" />
                                            </a>                              
                                            
                                        @else
                                            <p>No image available</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row col-md-10">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="poin">Sertifikat/Piala</label>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <div class="custom-file" bis_skin_checked="1">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept=".png, .jpeg" >
                                            <label class="custom-file-label" for="customFile">Ubah file</label>
                                        </div>
                                    </div>
                                </div>

                            </div> 
                        </div>
                        
                        <div class="card-footer pt-0">
                            {{-- <a href="{{ URL::previous() }}">Go Back</a> --}}
                            {{-- <a href="{{ URL::previous() }}" class="btn btn-lg btn-primary float-right">Kembali</a> --}}
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    
    <script>
        function validatePrestasiAndPenyelenggara(input) {
            input.value = input.value.replace(/[^a-zA-Z0-9-: ]/g, '');
        }

        function validatePoin(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        Fancybox.bind("[data-fancybox]", {
        // Your custom options
        });
    </script>

    <script>    
        document.addEventListener("DOMContentLoaded", function () {

        // Initialize Select2 for the initial nama field
        $('.select2').select2({
            placeholder: 'Pilih Nama Siswa',
        });

        const addButton = document.querySelector("#tambahNamaSiswa");
        const container = document.querySelector("#NamaSiswaBaru");
        const namaFields = document.querySelector("#namaFields");
        let index = {{ isset($index) ? $index + 1 : 1 }};


        addButton.addEventListener("click", function () {
            const newRow = `
            <div style="margin-bottom:10px"id="baris-${index}">
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

        // Use event delegation to handle "Hapus" button clicks
        namaFields.addEventListener("click", function (event) {
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
        background-color: transparent;
        border: none;
        color: red;
        cursor: pointer;
        font-size: 20px;
        padding: 0; /* Menghilangkan padding agar menjadi bulat */
        margin-left: 500px;
        margin-top: -34px; Menempelkan tombol ke sisi kiri
        position: absolute; /* Menjadikan tombol sebagai posisi absolut */
    }

</style>






