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
                <h1>Data Prestasi Siswa</h1>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h4>Data Prestasi {{$siswa->nisn}} - {{$siswa->nama}}</h4>
                        @if (Auth::user()->role == 'admin')
                        <div class="card-header-action">
                            <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn], ['siswa' => $siswa]) }}" class="btn btn-warning">Detail Siswa</a>
                            <a href="{{ route('rapor.edit', ['nisn' => $siswa->nisn, 'siswa' => $siswa]) }}" class="btn btn-primary">Rapor Siswa</a>
                            <a href="{{ route('prestasi.create', ['siswa' => $siswa]) }}" class="btn btn-info" >+ Tambah Prestasi</a>
                        </div>
                        @endif
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
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table-striped mb-0 table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Prestasi</th>
                                        <th>Tingkat</th>
                                        <th>Nama Siswa</th>
                                        <th>Waktu</th>
                                        <th>Poin</th>
                                        <th>Status</th>
                                        @if (Auth::user()->role == 'admin')
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prestasis as $prestasi)
                                    <tr style="font-size: 14px;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $prestasi->prestasi }}</td>
                                        <td>{{ $prestasi->tingkat }}</td>
                                        <td>
                                            <ul class="bullet-list">
                                                @foreach($prestasi->siswas as $siswa)
                                                <li>{{ $siswa->nama }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($prestasi->waktu)->isoFormat('D MMM Y') }}</td>
                                        <td>{{ $prestasi->poin }}</td>
                                        <td>
                                            <div class="badge badge-{{ $prestasi->status === 'verified' ? 'success' : ($prestasi->status === 'unverified' ? 'warning' : 'danger') }}">
                                                {{ $prestasi->status }}
                                            </div>
                                        </td>
                                        @if (Auth::user()->role == 'admin')
                                        <td>
                                            <a class="btn btn-md mb-1 btn-primary fancybox" data-fancybox-type="ajax"><i class="fas fa-download"></i></a>
                                            <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="btn btn-md mb-1 btn-warning"><i class="fas fa-edit"></i></a>
                                            <form method="POST" action="{{ route('prestasi.destroy', ['prestasi' => $prestasi]) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-md mb-1 btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10">
                                            Data Prestasi Belum Tersedia.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="card-footer text-center" bis_skin_checked="1">
                                <nav class="d-inline-block">
                                <ul class="pagination mt-3 mb-1">
                                    {{ $prestasis->links('pagination::bootstrap-4') }}
                                </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!-- import Excel File Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="kelas">Kelas:</label>
                        <div class="row">
                            <div class="col">
                                <select class="form-control" id="kelasBerapa" name="kelasBerapa" required>
                                    <option value="">Pilih Kelas Berapa</option>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="peminatanApa" name="peminatanApa" required>
                                    <option value="">Pilih Peminatan</option>
                                    <option value="MIPA">MIPA</option>
                                    <option value="IPS">IPS</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="kelasApa" name="kelasApa" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="form-group">
                        <label for="semester">Semester:</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="excelFile">Pilih Excel:</label>
                        <label style="color:red">*Maks 1 file per Upload</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                    </div>

                    <button type="submit" class="btn btn-primary">IMPORT</button>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
        $(document).ready(function() {
            $('.fancybox').click(function() {
                $.fancybox.open({
                    src: $(this).attr('href'),
                    type: 'ajax',
                    opts: {
                        width: '80%',
                        height: 'auto',
                        autoSize: false,
                    }
                });

                return false;
            });
        });
</script>
<script>
    function validateSearchInput(input) {
        // Use a regular expression to allow letters, numbers, spaces, and hyphens
        input.value = input.value.replace(/[^a-zA-Z0-9\s\-]/g, '');
    }
</script>


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
