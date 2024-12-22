@extends('layouts.app')

@section('title', 'List Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content" style="min-height: 535px;">
        <section class="section">
            <div class="section-header">
                <h1>Data siswa</h1>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h4>Data Siswa </h4>
                        <div class="card-header-action pr-2">
                            <a class="btn btn-success" data-toggle="modal" data-target="#importModal">Import Excel</a>
                        </div>
                        <div class="card-header-action">
                            <a href="/siswa/create" class="btn btn-primary" >+ Tambah Siswa</a>
                        </div>
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

                    <form action="{{ route('siswa.index') }}" style=" margin-left: 20px; margin-top:10px" method="GET">
                        <div class="input-group">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Ketik NISN, Nama" value="{{ request('search') }}" oninput="validateSearchInput(this)">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter by Kelas Dropdowns -->
                            @php
                            $kelasOptions = [
                                ['Pindahan', 'X - MIPA 1', 'X - MIPA 2', 'X - MIPA 3', 'X - MIPA 4', 'X - MIPA 5', 'X - MIPA 6', 'X - MIPA 7', 'X - MIPA 8', 'X - IPS 1', 'X - IPS 2'],
                                ['Pindahan', 'XI - MIPA 1', 'XI - MIPA 2', 'XI - MIPA 3', 'XI - MIPA 4', 'XI - MIPA 5', 'XI - MIPA 6', 'XI - MIPA 7', 'XI - MIPA 8', 'XI - IPS 1', 'XI - IPS 2'],
                                ['XII - MIPA 1', 'XII - MIPA 2', 'XII - MIPA 3', 'XII - MIPA 4', 'XII - MIPA 5', 'XII - MIPA 6', 'XII - MIPA 7', 'XII - MIPA 8', 'XII - IPS 1', 'XII - IPS 2'],
                            ];
                            $dropdownKelas = ['kelasX', 'kelasXI', 'kelasXII']; // Change this based on your needs
                            @endphp

                            @for ($i = 0; $i < count($dropdownKelas); $i++)
                            <div class="col-md-2">
                                <div class="form-group">
                                    @php
                                    $kelasKey = $dropdownKelas[$i];
                                    @endphp
                                    <div class="dropdown" id="{{ $kelasKey.'Dropdown' }}">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="{{ $kelasKey.'DropdownButton' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Filter {{  $kelasKey }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="{{ $kelasKey.'DropdownButton' }}">
                                            @php
                                            $kelasValues = $kelasOptions[$i];
                                            @endphp
                                            @foreach ($kelasValues as $index => $kelasValue)
                                            <div class="form-check dropdown-item">
                                                <input class="form-check-input" type="checkbox" name="{{ $kelasKey }}[]" value="{{ $kelasValue }}" id="{{ $kelasKey . '_' . $index }}" {{ in_array($kelasValue, request($kelasKey, [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $kelasKey . '_' . $index }}">{{  $kelasValue }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            
                        </form>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table-striped mb-0 table">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>No.</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Kelas X</th>
                                        <th>Kelas XI</th>
                                        <th>Kelas XII</th>
                                        <th >Aksi</th>

                                    </tr>
                                </thead>
                                <tbody> 
                                    @php $no = ($siswas->currentPage() - 1) * $siswas->perPage() + 1 @endphp

                                    @forelse ($siswas as $siswa)
                                        <tr style="font-size: 14px;">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $siswa->nisn }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $siswa->kelas_10 }}</td>
                                            <td>{{ $siswa->kelas_11 }}</td>
                                            <td>{{ $siswa->kelas_12 }}</td>
                                            
                                            <td style="padding-right: 12px; padding-left: 12px">
                                                {{-- <a href="{{ route('siswa.read', ['nisn' => $siswa->nisn]) }}" class="btn btn-sm btn-success fancybox" data-fancybox-type="ajax"><i class="fas fa-eye"></i></a> --}}
                                                <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('rapor.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-primary fancybox" data-fancybox-type="ajax"><i class="fas fa-newspaper"></i></a>
                                                <a href="{{ route('siswa.prestasi', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-info fancybox"  data-fancybox-type="ajax"><i class="fas fa-award"></i></a>
                                                <form action="{{ route('siswa.destroy', ['siswa' => $siswa])}}" method="POST" class="d-inline" id="delete-form-{{$siswa->nisn}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger trigger-fire-modal-7" data-confirm="Yakin?| ingin menghapus data {{$siswa->nisn}} - {{$siswa->nama}}?" data-confirm-yes="submitDel('{{$siswa->nisn}}')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    
                                    @empty
                                    <tr>
                                        <td colspan="10">
                                            Data siswa belum Tersedia.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                            <div class="card-footer text-center" bis_skin_checked="1">
                                <nav class="d-inline-block">
                                <ul class="pagination mt-3 mb-1">
                                    {{-- {{ $siswas->links('pagination::bootstrap-4') }} --}}
                                    {{ $siswas->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
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
                        <label style="color:red">*Maks 1 file per upload</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                            <label class="custom-file-label" for="customFile">Browse file</label>
                        </div>
                    </div>
                    
                    <button type="submit" style="float: right;" class="btn btn-primary">IMPORT</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection




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

    document.getElementById('excel_file').addEventListener('change', function(event) {
        var input = event.target;
        var label = input.nextElementSibling;
        var fileName = input.files[0].name;
        label.textContent = fileName;
    }); 

    function submitDel(siswaNisn) {
        const form = document.getElementById('delete-form-' + siswaNisn);
        form.submit();
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
