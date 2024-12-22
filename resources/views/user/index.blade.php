@extends('layouts.app')

@section('title', 'List User')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content" style="min-height: 709px;" bis_skin_checked="1">
        <section class="section">
            <div class="section-header" bis_skin_checked="1">
                <h1>Data User</h1>
            </div>

                <div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Siswa </h4>
                            <div class="card-header-action pr-2">
                                <a href="{{ route('user.generateAkunSiswa') }}" class="btn btn-info" >Generate Akun Siswa</a>
                            </div>
                            <div class="card-header-action">
                                <a  class="btn btn-primary" data-toggle="modal" data-target="#addAdminModal" >+ Tambah Admin</a>
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
    
                        <form action="{{ route('user.index') }}" style=" margin-left: 20px; margin-top:10px" method="GET">
                            <div class="input-group">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Ketik NISN, Username" value="{{ request('search') }}" oninput="validateSearchInput(this)">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Filter by Kelas Dropdowns -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="dropdown" id="roleDropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="roleDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Filter role
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="roleDropdownButton">
                                                <div class="form-check dropdown-item">
                                                    <input class="form-check-input" type="checkbox" name="role[]" value="admin" id="role_admin" {{ in_array('admin', request('role', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_admin">Admin</label>
                                                </div>
                                                <div class="form-check dropdown-item">
                                                    <input class="form-check-input" type="checkbox" name="role[]" value="siswa" id="role_siswa" {{ in_array('siswa', request('role', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_siswa">Siswa</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
    
                        <div class="card-body p-4 col-12">
                            <div class="table-responsive">
                                <table class="table-striped mb-0 table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Username/NISN</th>
                                            <th>role</th>
                                            <th >Aksi</th>
    
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @php $no = ($users->currentPage() - 1) * $users->perPage() + 1 @endphp
    
                                        @forelse ($users as $user)
                                            {{-- @if($user->name == "admin")
                                                @continue
                                            @endif --}}
                                            <tr style="font-size: 14px;">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role }}</td>
                                                
                                                
                                                <td style="padding-right: 12px; padding-left: 12px">
                                                    <a class="btn btn-md btn-warning update-password-button"  data-toggle="modal" data-target="#editPasswordModal-{{$user->id}}" data-item="{{ $user->id }}"><i class="fas fa-key"></i></a>
                                                        <form method="POST" action="{{ route('user.destroyUser', ['user' => $user]) }}" class="d-inline" id="delete-form-{{$user->id}}">
                                                        {{-- <form action="{{ route('user.destroyUser', ['user' => $user])}}" method="POST" class="d-inline" id="delete-form-{{$user->id}}"> --}}
                                                        @csrf
                                                        @method('DELETE')
                                                            <button type="submit" class="btn btn-danger trigger-fire-modal-7 mb-1 " data-confirm="Yakin?| ingin menghapus user {{$user->role}} - {{$user->name}}?" data-confirm-yes="submitDel('{{$user->id}}')" disabled><i class="fas fa-trash" ></i></button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <div class="modal fade" data-backdrop="false" id="editPasswordModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editPasswordLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editPasswordLabel">Edit Password Akun</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('user.updatePassword', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="text" name="id" class="form-control" value="{{$user->id}}" hidden>
                                                                
                                                                <div class="form-group col-sm-10">
                                                                    <label class="form-label" for="name">Nama</label>
                                                                    <input type="text" name="name" class="form-control" value="{{$user->name}}" readonly>
                                                                    <div class="invalid-feedback">
                                                                        Please fill in the field
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-10">
                                                                    <label class="form-label" for="email">Username/NISN</label>
                                                                    <input type="text" name="email" class="form-control" value="{{$user->email}}" readonly>
                                                                    <div class="invalid-feedback">
                                                                        Please fill in the field
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-10">
                                                                    <label class="form-label" for="passwordBaru">Password Baru</label>
                                                                    <input type="password" name="passwordBaru" id="passwordBaru-{{$user->id}}" oninput="validatePassword(this)" class="form-control{{ $errors->has('passwordBaru') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                                                                    <div class="invalid-feedback">
                                                                        Please fill in the field
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-10">
                                                                    <label class="form-label" for="konfirmasiPassword">Konfirmasi Password</label>
                                                                    <input type="password" name="konfirmasiPassword" id="konfirmasiPassword-{{$user->id}}" oninput="validateKonfirmasiPassword(this)" class="form-control{{ $errors->has('konfirmasiPassword') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                                                                    <div class="invalid-feedback">
                                                                        Please fill in the field
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer text-right" bis_skin_checked="1">
                                                                    <button class="btn btn-primary">Save Password</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        <tr>
                                            <td colspan="10">
                                                Data User belum Tersedia.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    
                                </table>
                                <div class="card-footer text-center" bis_skin_checked="1">
                                    <nav class="d-inline-block">
                                    <ul class="pagination mt-3 mb-1">
                                        {{ $users->links('pagination::bootstrap-4') }}
                                    </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    </div>

<div class="modal fade" data-backdrop="false" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminLabel">Tambah User Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.storeAdmin') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-sm-10">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="name" class="form-control" value="" >
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>
                    <div class="form-group col-md-10">
                        <label class="form-label" for="email">Username</label>
                        <input type="text" name="email" class="form-control" value="" >
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>
                    <div class="form-group col-md-10">
                        <label class="form-label" for="passwordBaru">Password</label>
                        <input type="password" name="passwordBaru" id="passwordBaruAddModal" oninput="validatePassword(this)" class="form-control{{ $errors->has('passwordBaru') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>
                    <div class="form-group col-md-10">
                        <label class="form-label" for="konfirmasiPassword">Konfirmasi Password</label>
                        <input type="password" name="konfirmasiPassword" id="konfirmasiPasswordAddModal" oninput="validateKonfirmasiPasswordAddModal(this)" class="form-control{{ $errors->has('konfirmasiPassword') ? ' is-invalid' : '' }}" value="" required autocomplete="new-password">
                        <div class="invalid-feedback">
                            Please fill in the field
                        </div>
                    </div>
                    <div class="modal-footer text-right" bis_skin_checked="1">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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



    window.addEventListener('load', () => {
        document.querySelectorAll('.trigger-fire-modal-7').forEach(button => {
            button.disabled = false; // Re-enable all delete buttons when the page is fully loaded
        });
    });

// Function to prevent immediate submission and show confirmation dialog
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.trigger-fire-modal-7').forEach(button => {
            // Initially disable the button to prevent accidental submission
            button.disabled = true;

            // Add event listener for the delete button
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the form from submitting immediately
                
            //     const confirmationMessage = this.getAttribute('data-confirm');
            //     const confirmYes = this.getAttribute('data-confirm-yes');

            //     // Show confirmation dialog
            //     if (confirm(confirmationMessage)) {
            //         eval(confirmYes); // Call the function to submit the form if confirmed
            //     }
            });
        });

        // Enable buttons after page has fully loaded
        window.addEventListener('load', () => {
            document.querySelectorAll('.trigger-fire-modal-7').forEach(button => {
                button.disabled = false;
            });
        });
    });


    function submitDel(userId) {
        const form = document.getElementById('delete-form-' + userId);
        if (form) {
            // alert('Form found');
            form.submit();
        } else {
            alert('Form not found. Please check the ID: delete-form-' + userId);
        }
    }

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
        // Extract the user ID from the input element's ID
        var userId = input.id.split('-')[1];

        // Get the value of passwordBaru for the current user
        var passwordBaru = document.getElementById('passwordBaru-' + userId).value;

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

    function validateKonfirmasiPasswordAddModal(input) {
        // Get the value of passwordBaru
        var passwordBaru = document.getElementById('passwordBaruAddModal').value;

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
