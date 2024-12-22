@extends('layouts.app')

@section('title', 'Eligible SNBP')

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

            <div class="card">
                <div class="card-header">
                    <h4> Hasil SPK Eligible SNBP </h4>
                    <div class="card-header-action pr-2">
                        @if (Auth::user()->role == 'admin')
                        <a class="btn btn-primary" onclick="openSettingSPK()">Setting Bobot Kriteria</a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#kuotaSnbpsModal">Setting Kuota SNBP</button>
                        @endif
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
                    <div class="row mt-2 ml-4">
                        <div class="col-md-11">
                            <table class="table-striped mb-0 mr-0 table">
                                <tbody>
                                    <tr>
                                        @foreach($bobotKriterias as $bobot)
                                            <td>Bobot {{ $bobot->nama_kriteria }}: {{$bobot->bobot}}</td>
                                        @endforeach
                                        @foreach($kuotaSnbps as $kuota)
                                        <td>Kuota {{ $kuota->peminatan }}: {{$kuota->kuota}}</td>
                                    @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="input-group mt-3">
                        <!-- Search input -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Ketik NISN, Nama" value="{{ request('search') }}" oninput="validateSearchInput(this)">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    
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
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="mipa-table">
                            @include('partials.spkTable', ['siswas' => $siswasMIPA, 'peminatan' => 'MIPA', 'rank' => $startRankMIPA] )
                        </div>
                        <div class="tab-pane fade" id="ips-table">
                            @include('partials.spkTable', ['siswas' => $siswasIPS, 'peminatan' => 'IPS', 'rank' => $startRankIPS])
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>



<!-- Modal for Kriteria Bobots -->
<div class="modal fade" id="bobotKriteriasModal" tabindex="-1" role="dialog" aria-labelledby="bobotKriteriasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('spk.setBobot') }}" method="POST" enctype="multipart/form-data" id="bobotForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bobotKriteriasModalLabel">Setting Bobot Kriteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mb-0 pb-0">
                    <div class="form-group">
                        <div class="row mb-2">
                            @foreach($bobotKriterias as $bobot)
                                <div class="col-md-4">
                                    <label for="{{ $bobot->nama_kriteria }}Bobot">Bobot {{ $bobot->nama_kriteria }}:</label>
                                    <input type="number" step="0.05" min="0" max="1" name="{{ $bobot->nama_kriteria }}Bobot" id="{{ $bobot->nama_kriteria }}Bobot" class="form-control" value="{{ $bobot->bobot }}" oninput="validateTotalBobot()">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <label style="color:red">*Total seluruh Bobot harus berjumlah 1</label>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  onclick="saveBobotKriterias()" id="saveBobotBtn" disabled>Save</button>
                </div>
            </div>
        </form>
    </div>    
</div>

<!-- Modal for Kuota SNBP Bobots -->
<div class="modal fade" id="kuotaSnbpsModal" tabindex="-1" role="dialog" aria-labelledby="kuotaSnbpsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('spk.setKuota') }}" method="POST" enctype="multipart/form-data" id="kuotaForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kuotaSnbpsModalLabel">Setting Kuota SNBP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mb-0 pb-0">
                    <div class="form-group">
                        <div class="row mb-2">
                            @foreach($kuotaSnbps as $kuota)
                                <div class="col-md-5">
                                    <label for="{{ $kuota->peminatan }}Kuota">Kuota {{ $kuota->peminatan }}:</label>
                                    <input type="number" step="1" min="0" max="100" name="{{ $kuota->peminatan }}Kuota" id="{{ $kuota->peminatan }}Kuota" class="form-control" value="{{ $kuota->kuota }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveKuotaSnbps()" id="saveKuotaBtn" >Save</button>
                </div>
            </div>
        </form>
    </div>    
</div>
@endsection


@push('scripts')
{{-- <script>
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
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve the active tab from localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            var tabElement = document.querySelector('a[href="' + activeTab + '"]');
            if (tabElement) {
                var bootstrapTab = new bootstrap.Tab(tabElement);
                bootstrapTab.show();
            }
        }

        // Add event listeners to tab links
        var tabs = document.querySelectorAll('.nav-tabs a');
        tabs.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(event) {
                var href = event.target.getAttribute('href');
                localStorage.setItem('activeTab', href);
            });
        });
    });

    // Store a test value
localStorage.setItem('testKey', 'testValue');

// Retrieve the test value
console.log(localStorage.getItem('testKey')); // Should output 'testValue'

</script>

<script>
    function validateSearchInput(input) {
        // Use a regular expression to allow letters, numbers, spaces, and hyphens
        input.value = input.value.replace(/[^a-zA-Z0-9\s\-]/g, '');
    }
    function validateKuotaInput(input) {
        // Use a regular expression to allow letters, numbers, spaces, and hyphens
        input.value = input.value.replace(/[^a-zA-Z0-9\s\-]/g, '');
    }
</script>
<script>
    // Script Setting Bobot Modal
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
    function openSettingSPK() {
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

    // Script Setting Kuota Modal
   // Function to open the modal
    function openSettingKuota() {
        $('#kuotaSnbpsModal').modal('show');
    }

    // Function to save Kuota settings
    function saveKuotaSettings() {
        // Collect the data you want to send to the server
        var formData = {
            // Add data here if needed
        };

        // Perform an AJAX request
        $.ajax({
            type: 'PUT',
            url: '{{ route("spk.setKuota") }}',
            data: formData,
            success: function(response) {
                // Handle the success response, if needed
                console.log(response);
            },
            error: function(error) {
                // Handle the error, if needed
                console.error(error);
            }
        });
    }

    // Bind the save button click event
    $('#saveKuotaBtn').on('click', function() {
        saveKuotaSettings();
    });

    // Function to open the modal
    function openSettingSPK() {
        $('#bobotKriteriasModal').modal('show');
    }

    function saveKuotaSnbps() {
        // Validate the total bobot
        
        // Submit the form (assuming you have a form element)
        document.getElementById('kuotaForm').submit();
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

