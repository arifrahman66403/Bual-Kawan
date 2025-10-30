<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Profesional</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-width: 250px;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030; /* Di bawah navbar */
            overflow-y: auto;
            padding-top: 56px; /* Tinggi Navbar */
            transition: all 0.3s;
        }

        /* Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        /* Mengatur agar Navbar berada di atas dan tidak terhalang oleh sidebar */
        .navbar {
            z-index: 1040;
        }

        /* Menu Sidebar (Contoh untuk menu aktif) */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: var(--bs-primary); /* Warna dasar Bootstrap */
            font-weight: bold;
        }

        /* Responsive: Sembunyikan Sidebar pada perangkat kecil */
        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(var(--sidebar-width) * -1); /* Sembunyikan ke kiri */
            }
            .main-content {
                margin-left: 0;
            }
            /* Jika tombol toggle diaktifkan, ganti class */
            .sidebar.active {
                left: 0;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container-fluid">
            <button class="btn btn-dark d-lg-none me-3" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-speedometer2 me-2"></i> Aplikasi Admin
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'Administrator' }} 
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill me-2"></i> Pengaturan Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="sidebar bg-dark shadow-lg" id="sidebar">
        <div class="d-flex flex-column p-3">
            <h6 class="text-white opacity-50 mt-2 mb-3 text-uppercase">Menu Utama</h6>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link active">
                        <i class="bi bi-house-door-fill me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('tracking.index') }}" class="nav-link">
                        <i class="bi bi-qr-code-scan me-2"></i> Tracking Kunjungan
                    </a>
                </li>
                <li>
                    <a href="{{ route('qr.index') }}" class="nav-link">
                        <i class="bi bi-qr-code me-2"></i> Manajemen QR Code
                    </a>
                </li>
                
                <h6 class="text-white opacity-50 mt-4 mb-3 text-uppercase">Data Master</h6>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-people-fill me-2"></i> Data Pengunjung
                    </a>
                </li>
                
                @if (Auth::user()->role === 'superadmin')
                <h6 class="text-white opacity-50 mt-4 mb-3 text-uppercase">Administrasi</h6>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-shield-lock-fill me-2"></i> Manajemen Admin
                    </a>
                </li>
                @endif
            </ul>

            <hr class="text-white opacity-25">
            <div class="text-center text-white opacity-75 small">
                Role: <span class="badge bg-primary">{{ ucfirst(Auth::user()->role ?? 'Guest') }}</span>
            </div>
        </div>
    </div>
    <div class="main-content" id="main-content">
        {{ $slot }}
        
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md text-center text-muted">
                    &copy; 2024 Aplikasi Tracking. Hak Cipta Dilindungi.
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            // Logika untuk toggle sidebar di perangkat kecil
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>