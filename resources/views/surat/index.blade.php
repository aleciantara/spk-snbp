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
                <h1>Surat</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4> List Template Surat </h4>
                    @if (Auth::user()->role == 'admin')
                        <div class="card-header-action pr-2">
                            <a class="btn btn-primary" data-toggle="modal" data-target="#addTemplate">Tambah Template</a>
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
                                    @if (Auth::user()->role == 'siswa')
                                        <th>Surat</th>
                                        <th>Download</th>
                                    @elseif (Auth::user()->role == 'admin')
                                        <th>Surat</th>
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                <tr style="font-size: 14px;">
                                    <td>{{ $loop->iteration }}</td>
                                    @if (Auth::user()->role == 'siswa')
                                        <td>{{$template->surat}}</td>
                                        <td><a href="{{ route('surat.downloadTemplate', ['template' => $template]) }}" target="_blank">Download Template</a></td>
                                    @elseif (Auth::user()->role == 'admin')
                                        <td><a href="{{ route('surat.downloadTemplate', ['template' => $template]) }}" target="_blank">Download {{ $template->surat }}</a></td>
                                        <td>
                                            <form method="POST" action="{{ route('surat.destroyTemplate', ['template' => $template]) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-md mb-1 btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus template surat ini?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">
                                        Template Surat Belum Tersedia.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                                
                        </table>
                        {{-- <div class="card-footer text-center" bis_skin_checked="1">
                            <nav class="d-inline-block">
                            <ul class="pagination mt-3 mb-1">
                                {{ $surats->links('pagination::bootstrap-4') }}
                            </ul>
                            </nav>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4> List Surat </h4>
                    @if (Auth::user()->role == 'siswa')
                    <div class="card-header-action pr-2">
                        <a class="btn btn-primary" data-toggle="modal" data-target="#addSurat">Ajukan Surat</a>
                    </div>
                    @endif
                </div>


                @if (Auth::user()->role == 'admin')
                <form action="{{ route('surat.index') }}" style="margin-left: 20px; margin-top:10px" method="GET">
                    <div class="input-group">
                        <!-- Search input -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Ketik NISN, Nama" value="{{ request('search') }}" oninput="validateSearchInput(this)">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </div>
                
                        <!-- Status filter dropdown -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="dropdown" id="statusDropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="statusDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter Status
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="statusDropdownButton">
                                        <div class="form-check dropdown-item">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="verified" id="status_verified" {{ in_array('verified', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_verified">Verified</label>
                                        </div>
                                        <div class="form-check dropdown-item">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="unverified" id="status_unverified" {{ in_array('unverified', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_unverified">Unverified</label>
                                        </div>
                                        <div class="form-check dropdown-item">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="denied" id="status_denied" {{ in_array('denied', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_denied">Denied</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table-striped mb-0 table">
                            <thead>
                                @if (Auth::user()->role == 'siswa')
                                <tr>
                                    <th>No.</th>
                                    <th>Judul Surat</th>
                                    <th>Catatan</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                                @elseif (Auth::user()->role == 'admin')
                                <tr>
                                    <th>No.</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Judul Surat</th>
                                    <th>Catatan</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                                @endif
                            </thead>
                            <tbody>
                                @forelse($surats as $surat)
                                <tr style="font-size: 14px;">
                                    <td>{{ $loop->iteration }}</td>
                                    @if (Auth::user()->role == 'admin')
                                    <td>{{$surat->siswa->nisn}}</td>
                                    <td>                
                                        <a href="{{ route('siswa.edit', ['nisn' => $surat->siswa->nisn]) }}" style="color:rgba(0, 0, 0, 0.664)">{{ $surat->siswa->nama }}</a>
                                    </td>
                                    @endif
                                    <td>{{ $surat->judul }}</td>
                                    <td>{{ $surat->catatan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->created_at)->isoFormat('D MMM Y') }}</td>
                                    <td>
                                        <div class="badge badge-{{ $surat->status === 'verified' ? 'success' : ($surat->status === 'unverified' ? 'warning' : 'danger') }}">
                                            {{ $surat->status }}
                                        </div>
                                    </td>
                                    @if (Auth::user()->role == 'admin')
                                    <td>
                                        <a href="{{ route('surat.downloadSurat', ['surat' => $surat]) }}" target="_blank" class="btn btn-md mb-1 btn-primary fancybox" data-fancybox-type="ajax"><i class="fas fa-download"></i></a>
                                        <a href="{{ route('surat.editSurat', $surat->id) }}" class="btn btn-md mb-1 btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('siswa.edit', $surat->siswa->nisn) }}" class="btn btn-md mb-1 btn-info"><i class="fas fa-user"></i></a>
                                        <form method="POST" action="{{ route('surat.destroySurat', ['surat' => $surat]) }}" class="d-inline">
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
                                        Data Surat Belum Tersedia.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                                
                        </table>
                        {{-- <div class="card-footer text-center" bis_skin_checked="1">
                            <nav class="d-inline-block">
                            <ul class="pagination mt-3 mb-1">
                                {{ $surats->links('pagination::bootstrap-4') }}
                            </ul>
                            </nav>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<div class="modal fade" id="addTemplate" tabindex="-1" role="dialog" aria-labelledby="addTemplateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTemplateLabel">Upload Template Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('surat.storeTemplateSurat') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-md-10">
                        <label for="semester">Judul:</label>
                        <input type="text" name="surat" class="form-control" value="" required>
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="file">Pilih File Template:</label>
                        <label style="color:red">*Maks 1 file per Upload</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".pdf, .docx" required>
                    </div> 
                    <div class="modal-footer p-0">
                        <button type="submit" class="btn btn-primary" align="right">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSurat" tabindex="-1" role="dialog" aria-labelledby="addSuratLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSuratLabel">Upload Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('surat.storeSurat') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group ">
                        <label for="judul">Judul:</label>
                        <input type="text" name="judul" class="form-control" value="" placeholder="ex: Surat Pengunduran Diri dari SNBP" required>
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="catatan">Catatan:</label>
                        <textarea  style="min-height: 120px;" name="catatan" class="form-control" value="" placeholder="ex: Dikarenakan sudah diterima di Universitas X" required></textarea>
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="file">Pilih File Template:</label>
                        <label style="color:red">*Maks 1 file per Upload</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".pdf, .docx" required>
                    </div> 
                    <div class="modal-footer p-0">
                        <button type="submit" class="btn btn-primary" align="right">SAVE</button>
                    </div>
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

