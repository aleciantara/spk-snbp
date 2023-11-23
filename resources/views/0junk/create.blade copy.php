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
            <form method="POST" action="{{ route('siswa.store') }}" class="needs-validation" enctype="multipart/form-data" novalidate="">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Form Data Siswa</h4>
                        <!-- <div class="card-header-action">
                            <a href="/rapor/edit" class="btn btn-primary">Lihat Rapor</a>
                        </div> -->
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
                                    <input type="text" name="nisn" id="nisn" class="form-control{{ $errors->has('nisn') ? ' is-invalid' : '' }}" value="{{ old('nisn') }}" required>
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
                                    <input type="text" name="nama" id="nama" class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" value="{{ old('nama') }}" required>
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
                                        <option value="X - 1" {{ old('kelas_10') == 'X - 1' ? 'selected' : '' }}>X - 1</option>
                                        <option value="X - 2" {{ old('kelas_10') == 'X - 2' ? 'selected' : '' }}>X - 2</option>
                                        <option value="X - 3" {{ old('kelas_10') == 'X - 3' ? 'selected' : '' }}>X - 3</option>
                                        <option value="X - 4" {{ old('kelas_10') == 'X - 4' ? 'selected' : '' }}>X - 4</option>
                                        <option value="X - 5" {{ old('kelas_10') == 'X - 5' ? 'selected' : '' }}>X - 5</option>
                                        <option value="X - 6" {{ old('kelas_10') == 'X - 6' ? 'selected' : '' }}>X - 6</option>
                                        <option value="X - 7" {{ old('kelas_10') == 'X - 7' ? 'selected' : '' }}>X - 7</option>
                                        <option value="X - 8" {{ old('kelas_10') == 'X - 8' ? 'selected' : '' }}>X - 8</option>
                                        <option value="X - 9" {{ old('kelas_10') == 'X - 9' ? 'selected' : '' }}>X - 9</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose an option
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <select name="kelas_11" id="kelas_11" class="form-control{{ $errors->has('kelas_11') ? ' is-invalid' : '' }}" required>
                                        <option value="">Pilih Kelas XI</option>
                                        <option value="pindahan" {{ old('kelas_11') == 'Pindahan' ? 'selected' : '' }}>Pindahan</option>
                                        <option value="XI - 1" {{ old('kelas_11') == 'XI - 1' ? 'selected' : '' }}>XI - 1</option>
                                        <option value="XI - 2" {{ old('kelas_11') == 'XI - 2' ? 'selected' : '' }}>XI - 2</option>
                                        <option value="XI - 3" {{ old('kelas_11') == 'XI - 3' ? 'selected' : '' }}>XI - 3</option>
                                        <option value="XI - 4" {{ old('kelas_11') == 'XI - 4' ? 'selected' : '' }}>XI - 4</option>
                                        <option value="XI - 5" {{ old('kelas_11') == 'XI - 5' ? 'selected' : '' }}>XI - 5</option>
                                        <option value="XI - 6" {{ old('kelas_11') == 'XI - 6' ? 'selected' : '' }}>XI - 6</option>
                                        <option value="XI - 7" {{ old('kelas_11') == 'XI - 7' ? 'selected' : '' }}>XI - 7</option>
                                        <option value="XI - 8" {{ old('kelas_11') == 'XI - 8' ? 'selected' : '' }}>XI - 8</option>
                                        <option value="XI - 9" {{ old('kelas_11') == 'XI - 9' ? 'selected' : '' }}>XI - 9</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose an option
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <select name="kelas_12" id="kelas_12" class="form-control{{ $errors->has('kelas_12') ? ' is-invalid' : '' }}" required>
                                        <option value="">Pilih Kelas XII</option>
                                        <option value="XII - 1" {{ old('kelas_12') == 'XII - 1' ? 'selected' : '' }}>XII - 1</option>
                                        <option value="XII - 2" {{ old('kelas_12') == 'XII - 2' ? 'selected' : '' }}>XII - 2</option>
                                        <option value="XII - 3" {{ old('kelas_12') == 'XII - 3' ? 'selected' : '' }}>XII - 3</option>
                                        <option value="XII - 4" {{ old('kelas_12') == 'XII - 4' ? 'selected' : '' }}>XII - 4</option>
                                        <option value="XII - 5" {{ old('kelas_12') == 'XII - 5' ? 'selected' : '' }}>XII - 5</option>
                                        <option value="XII - 6" {{ old('kelas_12') == 'XII - 6' ? 'selected' : '' }}>XII - 6</option>
                                        <option value="XII - 7" {{ old('kelas_12') == 'XII - 7' ? 'selected' : '' }}>XII - 7</option>
                                        <option value="XII - 8" {{ old('kelas_12') == 'XII - 8' ? 'selected' : '' }}>XII - 8</option>
                                        <option value="XII - 9" {{ old('kelas_12') == 'XII - 9' ? 'selected' : '' }}>XII - 9</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose an option
                                    </div>
                                </div>
                            </div> 
                            <div class="form-row col-md-10" bis_skin_checked="1">
                                <div class="form-group col-md-2">
                                    <label class="form-label" for="kelas">Peminatan</label>
                                </div>
                                <div class="col-lg-4 col-md-6 pr-4" bis_skin_checked="1">
                                    <div class="selectgroup w-100" bis_skin_checked="1">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="value" value="MIPA" class="selectgroup-input" id="mipaRadio">
                                            <span class="selectgroup-button">MIPA</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="value" value="IPS" class="selectgroup-input" id="ipsRadio">
                                            <span class="selectgroup-button">IPS</span>
                                        </label>
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
                                        <option value="Sangat Baik" {{ old('sikap') == 'X - 1' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="Baik" {{ old('sikap') == 'X - 2' ? 'selected' : '' }}>Baik</option>
                                        <option value="Cukup" {{ old('sikap') == 'X - 3' ? 'selected' : '' }}>Cukup</option>
                                        <option value="Buruk" {{ old('sikap') == 'X - 4' ? 'selected' : '' }}>Buruk</option>
                                        <option value="Sangat Buruk" {{ old('sikap') == 'X - 5' ? 'selected' : '' }}>Sangat Buruk</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose an option
                                    </div>
                                </div>
                                
                            </div> 
                        </div>
                    </div>

                    <div class="card-header">
                        <h4>Form Rapor Siswa</h4>
                    </div>

                    <div class="card-body pb-0">
                        <div class="row m-4">
                            <div class="row col-sm-12">
                                <label for="matapelajaran" class="col-sm-4  col-form-label">Mata Pelajaran</label>
                                <label for="semester_1" class="col-sm-1 mr-2  ml-3 text-center col-form-label">SMT 1</label>
                                <label for="semester_2" class="col-sm-1 mr-2  ml-3 text-center col-form-label">SMT 2</label>
                                <label for="semester_3" class="col-sm-1 mr-2  ml-3 text-center col-form-label">SMT 3</label>
                                <label for="semester_4" class="col-sm-1 mr-2  ml-3 text-center col-form-label">SMT 4</label>
                                <label for="semester_5" class="col-sm-1 mr-2  ml-3 text-center col-form-label">SMT 5</label>
                                <label for="rata_rata" class="col-sm-1 mr-3  ml-2 text-center col-form-label">Rata P</label>
                            </div>

                            
                            @php
                                $subjects = [
                                    'Pendidikan Agama dan Budi Pekerti',
                                    'Pendidikan Pancasila dan kewarganegaraan',
                                    ' Matematika',
                                    'Sejarah Indonesia',
                                    'Bahasa Inggris',
                                    'Seni budaya',
                                    'Pendidikan Jasmani Olahraga dan Kesehatan',
                                    'Prakarya dan Kewirausahaan',
                                ];
                            @endphp
                            @foreach ($subjects as $subject)
                            <div class="container">
                                <div class="form-group row col-sm-12">
                                    <input style="width: 280px; height: 27px" type="text" name="matapelajaran" id="matapelajaran" class="mr-4 form-control-sm form-control{{ $errors->has('matapelajaran') ? ' is-invalid' : '' }}" value="{{ $subject }}" placeholder="{{ $subject }}" readonly>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                                    <input style="width: 36px; height: 27px" type="text" name="semester_2" id="semester_2" class="form-control-sm form-control{{ $errors->has('semester_2') ? ' is-invalid' : '' }}" value="{{ old('semester_2') }}" placeholder="P" required>
                                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_2" id="semester_2" class="form-control-sm mr-3 form-control{{ $errors->has('semester_2') ? ' is-invalid' : '' }}" value="{{ old('semester_2') }}" placeholder="K" required>

                                    <input style="width: 36px; height: 27px" type="text" name="semester_3" id="semester_3" class="form-control-sm form-control{{ $errors->has('semester_3') ? ' is-invalid' : '' }}" value="{{ old('semester_3') }}" placeholder="P" required>
                                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_3" id="semester_3" class="form-control-sm mr-3 form-control{{ $errors->has('semester_3') ? ' is-invalid' : '' }}" value="{{ old('semester_3') }}" placeholder="K" required>

                                    <input style="width: 36px; height: 27px" type="text" name="semester_4" id="semester_4" class="form-control-sm form-control{{ $errors->has('semester_4') ? ' is-invalid' : '' }}" value="{{ old('semester_4') }}" placeholder="P" required>
                                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_4" id="semester_4" class="form-control-sm mr-3 form-control{{ $errors->has('semester_4') ? ' is-invalid' : '' }}" value="{{ old('semester_4') }}" placeholder="K" required>

                                    <input style="width: 36px; height: 27px" type="text" name="semester_5" id="semester_5" class="form-control-sm form-control{{ $errors->has('semester_5') ? ' is-invalid' : '' }}" value="{{ old('semester_5') }}" placeholder="P" required>
                                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                                    <input style="width: 36px; height: 27px" type="text" name="semester_5" id="semester_5" class="form-control-sm mr-3 form-control{{ $errors->has('semester_5') ? ' is-invalid' : '' }}" value="{{ old('semester_5') }}" placeholder="K" required>

                                    <input style="width: 57px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm ml-2 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="" placeholder="Rata-rata" disabled readonly>

                                </div>
                            </div>
                            @endforeach

                            <h8 style="margin-bottom: 4px">Mata Pelajaran Peminatan</h8>

                            

                            <div class="container" id="mataPelajaranMIPA">
                                <!-- Baris baru akan ditambahkan di sini -->
                            </div>
                            <div class="container" id="mataPelajaranIPS">
                                <!-- Baris baru akan ditambahkan di sini -->
                            </div>

                            <div class="container" id="mataPelajaranBaru">
                                <!-- Baris baru akan ditambahkan di sini -->
                            </div>

                            <button type="button" id="tambahMataPelajaran" class="btn btn-light"
                                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                    + Mata Pelajaran
                            </button>

                    
                            </div>
                        </div>
                        
                        <div class="card-footer">
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

<script>    
    document.addEventListener("DOMContentLoaded", function () {
        const addButton = document.querySelector("#tambahMataPelajaran");
        const container = document.querySelector("#mataPelajaranBaru");
        let index = 1;

        addButton.addEventListener("click", function () {
            const newRow = `
            <div class="form-group row col-sm-12" id="baris-${index}">
                <button type="button" class="btn btn-danger btn-sm hapus-baris rounded-circle" data-index="${index}">x</button>
                <input style="width: 280px; height: 27px" type="text" name="matapelajaran_${index}" class="mr-4 form-control-sm form-control" required>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_1" class="form-control-sm form-control" maxlength="2" placeholder="P" required>
                <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_1" class="form-control-sm mr-3 form-control" maxlength="2" placeholder="K" required>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_2" class="form-control-sm form-control" maxlength="2" placeholder="P" required>
                <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_2" class="form-control-sm mr-3 form-control" maxlength="2" placeholder="K" required>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_3" class="form-control-sm form-control" maxlength="2" placeholder="P" required>
                <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_3" class="form-control-sm mr-3 form-control" maxlength="2" placeholder="K" required>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_4" class="form-control-sm form-control" maxlength="2" placeholder="P" required>
                <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_4" class="form-control-sm mr-3 form-control" maxlength="2" placeholder="K" required>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_5" class="form-control-sm form-control" maxlength="2" placeholder="P" required>
                <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                <input style="width: 36px; height: 27px" type="text" name="semester_${index}_5" class="form-control-sm mr-3 form-control" maxlength="2" placeholder="K" required>
                <input style="width: 57px; height: 27px" type="text" name="rata_rata_${index}" class="form-control-sm ml-2 form-control" placeholder="Rata-rata" disabled readonly>
            </div>
            `;
            container.insertAdjacentHTML("beforeend", newRow);
            index++;
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
    document.addEventListener("DOMContentLoaded", function () {
    const mipaRadio = document.querySelector("#mipaRadio");
    const ipsRadio = document.querySelector("#ipsRadio");
    const containerMIPA = document.querySelector("#mataPelajaranMIPA");
    const containerIPS = document.querySelector("#mataPelajaranIPS");

    const subjectsMIPA = ["Matematika (minat)", "Biologi", "Fisika", "Kimia"];
    const subjectsIPS = ["Geografi", "Sejarah (minat)", "Sosiologi", "Ekonomi"];

    function addRows(container, subjects) {

        subjects.forEach((subject) => {
            const newRow = `
                <div class="form-group row col-sm-12">
                    <input style="width: 280px; height: 27px" type="text" name="matapelajaran" id="matapelajaran" class="mr-4 form-control-sm form-control" value="${subject}" placeholder="${subject}" readonly>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="P" required>
                    <label style="font-size:13px" class=" mr-1 ml-1  col-form-label"> / </label>
                    <input style="width: 36px; height: 27px" type="text" name="semester_1" id="semester_1" class="form-control-sm mr-3 form-control{{ $errors->has('semester_1') ? ' is-invalid' : '' }}" value="{{ old('semester_1') }}" placeholder="K" required>

                    <input style="width: 57px; height: 27px" type="text" name="rata-rata_p" id="rata-rata_p" class="form-control-sm ml-2 form-control{{ $errors->has('rata-rata_p') ? ' is-invalid' : '' }}" value="" placeholder="Rata-rata" disabled readonly>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", newRow);
        });
    }

    mipaRadio.addEventListener("change", function () {
        if (mipaRadio.checked) {
            containerIPS.innerHTML = ""; // Clear existing rows
            addRows(containerMIPA, subjectsMIPA);
            alert("MIPA is selected");
        }
    });

    ipsRadio.addEventListener("change", function () {
        if (ipsRadio.checked) {
            containerMIPA.innerHTML = ""; // Clear existing rows
            addRows(containerIPS, subjectsIPS);
            alert("IPS is selected");
        }
    });
});
</script>




