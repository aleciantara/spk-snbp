@extends('layouts.app')

@section('title', 'Edit Password')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content" style="min-height: 709px;" bis_skin_checked="1">
        <section class="section">
            <div class="section-header" bis_skin_checked="1">
                <h1>Profile</h1>
            </div>
            <div class="section-body" bis_skin_checked="1">
                <h2 class="section-title">Hi, {{Auth::user()->name}}!</h2>
                <p class="section-lead">
                    Ubah Passwordmu di sini!
                </p>

                <div class="row mt-sm-4" bis_skin_checked="1">
                    <div class="col-12 col-md-12 col-lg-7" bis_skin_checked="1">
                        <div class="card" bis_skin_checked="1">
                            <form method="POST" action="{{ route('userSiswa.updatePassword') }}" c class="needs-validation" enctype="multipart/form-data" novalidate="">
                            @csrf
                            @method('PUT')
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                                <div class="card-header" bis_skin_checked="1">
                                    <h4>Ubah Password</h4>
                                </div>
                                <div class="form-row col-md-10 ml-4 mt-3">
                                    <div class="form-group col-md-3 pt-2">
                                        <label class="form-label" for="nisn">NISN</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="name" class="form-control" value="{{Auth::user()->email}}" readonly>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row col-md-10 ml-4 mt-3">
                                    <div class="form-group col-md-3 pt-2">
                                        <label class="form-label" for="nisn">Username</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}" readonly>
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row col-md-10 ml-4 mt-3">
                                    <div class="form-group col-md-3 pt-2">
                                        <label class="form-label" for="passwordBaru">Password Baru</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                    <input type="password" name="passwordBaru" id="passwordBaru" oninput="validatePassword(this)" class="form-control{{ $errors->has('passwordBaru') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row col-md-10 ml-4 mt-3">
                                    <div class="form-group col-md-3 pt-2">
                                        <label class="form-label" for="konfirmasiPassword">Konfirmasi Password</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{-- <input type="password" name="konfirmasiPassword" id="konfirmasiPassword" oninput="validateKonfirmasiPassword(this), validatePassword(this) " class="form-control{{ $errors->has('konfirmasiPassword') ? ' is-invalid' : '' }}" value="" required> --}}
                                        <input type="password" name="konfirmasiPassword" id="konfirmasiPassword" oninput="validateKonfirmasiPassword(this)" class="form-control{{ $errors->has('konfirmasiPassword') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                                        <div class="invalid-feedback">
                                            Please fill in the field
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right" bis_skin_checked="1">
                                    <button class="btn btn-primary">Save Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
    <script src="{{ asset('library/jquery-pwstrength/jquery.pwstrength.min.js')}}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    <script>
    function validatePassword(input) {
          // Get the input value
        var password = input.value;

          // Define the password requirements
        var lowercaseRegex = /[a-z]/;
        var uppercaseRegex = /[A-Z]/;
        var digitRegex = /\d/;

          // Check if the password meets the requirements
        var isValid = lowercaseRegex.test(password) && uppercaseRegex.test(password) && digitRegex.test(password) && password.length >= 8;

          // Get the corresponding error message element
        var errorElement = input.parentElement.querySelector('.invalid-feedback');

          // Update the error message and styling
        if (isValid) {
            errorElement.textContent = '';
            input.classList.remove('is-invalid');
        } else {
            errorElement.textContent = 'Password harus memiliki setidaknya 1 huruf kapital, 1 huruf kecil, 1 angka dan terdiri minimal 8 karakter.';
            input.classList.add('is-invalid');
        }
    }

    function validateKonfirmasiPassword(input) {
        // Get the value of passwordBaru
        var passwordBaru = document.getElementById('passwordBaru').value;

        // Get the value of konfirmasiPassword
        var konfirmasiPassword = input.value;

        // Get the corresponding error message element
        var errorElement = input.parentElement.querySelector('.invalid-feedback');

        // Check if konfirmasiPassword matches passwordBaru
        var isValid = passwordBaru === konfirmasiPassword;

        // Update the error message and styling
        if (isValid) {
            errorElement.textContent = '';
            input.classList.remove('is-invalid');
        } else {
            errorElement.textContent = 'Konfirmasi Password tidak sesuai dengan Password baru';
            input.classList.add('is-invalid');
        }
    }
</script>
@endpush
