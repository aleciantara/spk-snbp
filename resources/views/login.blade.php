<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
            name="viewport">
        <title>@yield('title')</title>
    
        <!-- General CSS Files -->
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png"/>
        <link rel="stylesheet"
            href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
            integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer" />
    
        @stack('style')
    
        <!-- Template CSS -->
        <link rel="stylesheet"
            href="{{ asset('css/style.css') }}">
        <link rel="stylesheet"
            href="{{ asset('css/components.css') }}">
    
        <!-- Start GA -->
        <script async
            src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
    
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
    
            gtag('config', 'UA-94034622-3');
        </script>
        <!-- END GA -->
    </head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Background Image --}}
            <div class="col-md-12" style="background: url('https://www.infoindependen.com/wp-content/uploads/2022/07/Kaltara.jpg'); background-size: cover; height: 100vh; position: relative;">

                {{-- Card Layout --}}
                <div class="col-md-4 offset-lg-7 " style="position: absolute; top: 50%; transform: translateY(-50%);">
                    <div class="card h-100">
                        <div class="card-body ">

                            {{-- Icon --}}
                            <div class="text-center pb-2">
                                <img src="{{ asset('icon.png') }}" alt="Icon" style="align-items: center" width="50" height="50">
                            </div>

                            {{-- Title --}}
                            <h5 class="card-title mb-2 text-center">LOGIN SPK ELIGIBLE SNBP</h5>

                            {{-- Information Card --}}
                            <div class="card mb-2" style="background-color: rgb(211, 238, 240)">
                                <div class="card-body">
                                    <p style="text-align: justify" ><b>Login siswa menggunakan password nilai rapor pengetahuan matematika semester 1 - 3 (Hapus angka dibelakang koma) </b></p>
                                    <p><b>Contoh: 8990100</b></p>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                            @endif

                            {{-- Login Form --}}
                            <form method="POST" action="">
                                @csrf

                                {{-- Username --}}
                                <div class="form-group mb-2">
                                    <label for="username">Username</label>
                                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="NISN"  autofocus>
                                    
                                </div>

                                {{-- Password --}}
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="******" >
                                </div>

                                {{-- Login Button --}}
                                <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- General JS Scripts -->
<script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
<script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
<script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('js/stisla.js') }}"></script>

@stack('scripts')

<!-- Template JS File -->
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
</body>

