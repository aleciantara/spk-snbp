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
                <h1>List Prestasi </h1>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">
                        <h4>Data Prestasi </h4>
                        
                        <div class="card-header-action">
                            <a href="/prestasi/create" class="btn btn-primary" >+ Tambah Prestasi</a>
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

                    <form action="{{ route('prestasi.index') }}" style="margin-left: 20px; margin-top:10px" method="GET">
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = ($prestasis->currentPage() - 1) * $prestasis->perPage() + 1 @endphp
                                    @forelse($prestasis as $prestasi)
                                    <tr style="font-size: 14px;">
                                        <td>{{ $no++ }}</td>
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
                                        <td>
                                            {{-- <a class="btn btn-md mb-1 btn-primary fancybox" data-fancybox-type="ajax"><i class="fas fa-download"></i></a> --}}
                                            <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="btn btn-md mb-1 btn-warning"><i class="fas fa-edit"></i></a>
                                            <form method="POST" action="{{ route('prestasi.destroy', ['prestasi' => $prestasi]) }}" class="d-inline" id="delete-form-{{$prestasi->id}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger trigger-fire-modal-7 mb-1" data-confirm="Yakin?| ingin menghapus data {{$prestasi->prestasi}}?" data-confirm-yes="submitDel('{{$prestasi->id}}')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
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


@push('scripts')

<script>
    function validateSearchInput(input) {
        // Use a regular expression to allow letters, numbers, spaces, and hyphens
        input.value = input.value.replace(/[^a-zA-Z0-9\s\-]/g, '');
    }

    function submitDel(prestasiId) {
        const form = document.getElementById('delete-form-' + prestasiId);
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
