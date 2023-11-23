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

            <div>
                <div class="card">
                    <div class="card-header">
                        <h4> Hasil SPK Eligible SNBP </h4>
                        <div class="card-header-action pr-2">
                            <!-- Add an onclick attribute to call a function that opens the modal -->
                            <a class="btn btn-primary" onclick="openBobotKriteriasModal()">Setting Bobot Kriteria</a>
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

                    <form action="{{ route('spk.index') }}" style="margin-left: 20px; margin-top:10px" method="GET">
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
                            <div class="col-md-7">
                                <table class="table-striped mb-0 table">
                                    <tbody>
                                        <tr>
                                            @foreach($bobotKriterias as $bobot)
                                                <td>Bobot {{ $bobot->nama_kriteria }} : {{$bobot->bobot*100}}%</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- @foreach($bobotKriterias as $bobot)
                            <div class="column mt-3 mr-4">
                                <h6>Bobot {{ $bobot->nama_kriteria }} : {{$bobot->bobot}}</h6>
                            </div>
                            @endforeach --}}
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
                        <div class="table-responsive">
                            <table class="table-striped mb-0 table">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Rapor</th>
                                        <th>Prestasi</th>
                                        <th>Sikap</th>
                                        <th>Total Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = ($siswas->currentPage() - 1) * $siswas->perPage() + 1 @endphp

                                    @forelse ($siswas as $siswa)
                                   
                                        <tr style="font-size: 14px;">
                                            <td># {{ $siswa->rank}}</td>
                                            <td>{{ $siswa->nisn }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $siswa->spk_preferensi->rapor ?? '-'}}</td>
                                            <td>{{ $siswa->spk_preferensi->prestasi ?? '-' }}</td>
                                            <td>{{ $siswa->spk_preferensi->sikap ?? '-' }}</td>
                                            <td>{{ $siswa->spk_preferensi->total ?? '-' }}</td>
                                            <td>
                                                {{-- <a href="{{ route('siswa.read', ['nisn' => $siswa->nisn]) }}" class="btn btn-sm btn-success fancybox" data-fancybox-type="ajax"><i class="fas fa-eye"></i></a> --}}
                                                <a href="{{ route('rapor.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-primary fancybox" data-fancybox-type="ajax"><i class="fas fa-newspaper"></i></a>
                                                <a href="{{ route('siswa.prestasi', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-info fancybox" data-fancybox-type="ajax"><i class="fas fa-award"></i></a>
                                                <a href="{{ route('siswa.edit', ['nisn' => $siswa->nisn]) }}" class="btn btn-md btn-warning"><i class="fas fa-edit"></i></a>
                                                {{-- <form action="{{ route('siswa.destroy', ['siswa' => $siswa])}}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-md btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')"><i class="fas fa-trash"></i></button>
                                                </form> --}}
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
                                    {{ $siswas->links('pagination::bootstrap-4') }}
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


<!-- Modal for Kriteria Bobots -->
<div class="modal fade" id="bobotKriteriasModal" tabindex="-1" role="dialog" aria-labelledby="bobotKriteriasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('spk.setBobot') }}" method="POST" enctype="multipart/form-data" id="bobotForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bobotKriteriasModalLabel">Set Kriteria Bobots</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p style="color: red; margin-bottom: 0;">*Bobot Kriteria harus memiliki total 1</p>
                    </div>
                   
                    <div class="form-group">
                        @foreach($bobotKriterias as $bobot)
                            <div class="row">
                                <label for="{{ $bobot->nama_kriteria }}Bobot">Bobot {{ $bobot->nama_kriteria }} :</label>
                                <input type="number" step="0.05" min="0" max="1" name="{{ $bobot->nama_kriteria }}Bobot" id="{{ $bobot->nama_kriteria }}Bobot" class="form-control" value="{{ $bobot->bobot }}" oninput="validateTotalBobot()">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  onclick="saveBobotKriterias()" id="saveBobotBtn" disabled>Save</button>
                </div>
            </div>
        </form>
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
<script>
    function validateTotalBobot() {
        // Calculate the total bobot
        var totalBobot = 0;
        var bobotInputs = document.querySelectorAll('[id$="Bobot"]');
        
        bobotInputs.forEach(function(input) {
            totalBobot += parseFloat(input.value) || 0;
        });

        // Enable/disable the Save button based on the total bobot
        var saveButton = document.getElementById('saveBobotBtn');
        saveButton.disabled = totalBobot !== 1;
    }

    // Function to open the modal
    function openBobotKriteriasModal() {
        $('#bobotKriteriasModal').modal('show');
    }

    function saveBobotKriterias() {
        // Validate the total bobot
        var totalBobot = 0;
        var bobotInputs = document.querySelectorAll('[id$="Bobot"]');
        
        bobotInputs.forEach(function(input) {
            totalBobot += parseFloat(input.value) || 0;
        });

        // Display alert if total bobot is not 1
        if (totalBobot !== 1) {
            alert('Total bobot kriteria must be equal to 1.');
            return;
        }

        // Submit the form (assuming you have a form element)
        document.getElementById('bobotForm').submit();
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

