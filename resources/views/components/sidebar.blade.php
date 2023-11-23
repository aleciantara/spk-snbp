<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">SPK SNBP SMAN 1 SMD</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            @auth
                @if (Auth::user()->role == 'admin')
                    <li class="menu-header">DASHBOARD</li>
                    <li class="nav-item dropdown">
                        <a href="/dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                    </li>

                    <li class="menu-header">CRUD SISWA</li>
                    <li class="nav-item dropdown">
                        <a href="/siswa/create" class="nav-link"><i class="fas fa-chalkboard-teacher"></i><span>Tambah Siswa</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/siswa" class="nav-link"><i class="fas fa-user-graduate"></i><span>List Siswa</span></a>
                    </li>

                    <li class="menu-header">CRUD Prestasi</li>
                    <li class="nav-item dropdown">
                        <a href="/prestasi/create" class="nav-link"><i class="fas fa-trophy"></i><span> Tambah Prestasi</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/prestasi" class="nav-link"><i class="fas fa-award"></i><span> List Prestasi</span></a>
                    </li>   
                    
                    <li class="menu-header">Sistem Pendukung Keputusan</li>
                    <li class="nav-item dropdown">
                        <a href="/spk" class="nav-link"><i class="fas fa-file-code"></i><span> SPK Eligible SNBP</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" onclick='window.open("/spkPrint");return false;'><i class="fas fa-print"></i><span> Print SPK Eligible SNBP</a>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a href="/spkKriteria" class="nav-link"><i class="fas fa-file-code"></i><span> Detail Nilai Kriteria</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/spkNormalisasi" class="nav-link"><i class="fas fa-file-code"></i><span> Detail Nilai Normalisasi</span></a>
                    </li> --}}
                    <li class="menu-header">Surat</li>
                    <li class="nav-item dropdown">
                        <a href="/surat" class="nav-link"><i class="fas fa-file-code"></i><span> List Surat</span></a>
                    </li>
                    <li class="menu-header">Setting Akun</li>
                    
                    <li class="nav-item dropdown">
                        <a href="/admin/editPassword" class="nav-link"><i class="fas fa-key"></i></i><span> Ubah Password</span></a>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a href="/admin/createUser" class="nav-link"><i class="fas fa-cog"></i></i><span> Tambah User</span></a>
                    </li> --}}
                    
                @elseif (Auth::user()->role == 'user')
                    <li class="menu-header">Dashboard</li>
                    <li class="nav-item dropdown">
                        <a href="/dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/profile-dosen" class="nav-link"><i class="fas fa-user"></i><span>Profile</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/matkul-dosen" class="nav-link"><i class="fas fa-book"></i><span>Matakuliah</span></a>
                    </li>

                @elseif (Auth::user()->role == 'siswa')
                <li class="menu-header">DASHBOARD</li>
                <li class="nav-item dropdown">
                    <a href="/dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>

                <li class="menu-header">DATA SISWA</li>
                <li class="nav-item dropdown">
                    <a href="/siswa/read" class="nav-link"><i class="fas fa-user"></i><span>Data Siswa</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a href="/rapor/read" class="nav-link"><i class="fas fa-newspaper"></i><span>Data Rapor</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a href="/prestasi/read" class="nav-link"><i class="fas fa-award"></i><span>Data Prestasi</span></a>
                </li>

                <li class="menu-header">PENGAJUAN</li>
                <li class="nav-item dropdown">
                    <a href="/prestasi/ajukan" class="nav-link"><i class="fas fa-trophy"></i><span> Ajukan Prestasi</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a href="/surat" class="nav-link"><i class="fas fa-file-pen"></i><span> Pengajuan Surat</span></a>
                </li>
                
                
                <li class="menu-header">Eligible SNBP</li>
                <li class="nav-item dropdown">
                    <a href="/spk/read" class="nav-link"><i class="fas fa-file-code"></i><span> Hasil Eligible SNBP</span></a>
                </li>
                
                {{-- <li class="nav-item dropdown">
                    <a href="#" onclick='window.open("/spkPrint");return false;'><i class="fas fa-print"></i><span> Print SPK Eligible SNBP</a>
                </li> --}}
                {{-- <li class="nav-item dropdown">
                    <a href="/spkKriteria" class="nav-link"><i class="fas fa-file-code"></i><span> Detail Nilai Kriteria</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a href="/spkNormalisasi" class="nav-link"><i class="fas fa-file-code"></i><span> Detail Nilai Normalisasi</span></a>
                </li> --}}
                <li class="menu-header">Setting Akun</li>
                
                <li class="nav-item dropdown">
                    <a href="/editPassword" class="nav-link"><i class="fas fa-key"></i></i><span> Ubah Password</span></a>
                </li>
                
                @endif
            @else
                <li class="menu-header">Dashboard</li>
                <li class="nav-item dropdown">
                    <a href="/admin-dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>
                <li class="menu-header">Authentication</li>
                <li class="nav-item dropdown">
                    <a href="/login" class="nav-link"><i class="fas fa-login"></i><span>Login</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a href="/register" class="nav-link"><i class="fas fa-register"></i><span>Register</span></a>
                </li>
            @endauth
        </ul>
        

        {{-- <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs"
                class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div> --}}
    </aside>
</div>
