<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bual Kawan - Dashboard Responsif</title>
    
    <link rel="icon" type="image/png" href="https://bualkawan.siakkab.go.id/logo-bualkawan2.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Aksen Warna Gen Z & Dark Mode Variables */
        :root {
            --genz-primary: #6c5ce7; /* Purple */
            --genz-secondary: #00cec9; /* Teal */
            --color-pink: #ff6b81;
            --color-yellow: #ff9f43;
            --color-green: #1dd1a1;
            
            --light-bg: #f5f7fa;
            --light-card-bg: #ffffff;
            --light-text: #212529;
            --light-border: #e0e0e0;
            
            /* Default: Light Mode */
            --body-bg: var(--light-bg);
            --card-bg: var(--light-card-bg);
            --text-color: var(--light-text);
            --muted-text: #6c757d;

            /* Gradient Colors for Light Mode Navbar */
            --nav-start-light: #00cec9; /* Teal */
            --nav-end-light: #6c5ce7; /* Purple */

            --navbar-text: #fff; /* Teks default putih di Navbar */
        }

        /* DARK MODE */
        .dark-mode {
            --body-bg: #1f1d2e; /* Dark Purple */
            --card-bg: #27253d; /* Darker Card */
            --text-color: #f5f7fa;
            --muted-text: #a0a0b0;
            --light-border: #444;

            /* Gradient Colors for Dark Mode Navbar */
            --nav-start-dark: #323050; /* Darker Purple */
            --nav-end-dark: #1f1d2e; /* Very Dark Purple */

            --navbar-text: #f5f7fa; /* Teks default light di Dark Mode Navbar */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            padding-top: 60px;
            /* Tambahkan padding bawah untuk Bottom Nav di mobile */
            padding-bottom: 70px; 
            transition: background-color 0.3s, color 0.3s;
        }
        
        /* --- Navbar Style Ciamik: GRADIENT --- */
        .navbar {
            background: linear-gradient(90deg, var(--nav-start-light), var(--nav-end-light));
            border-bottom: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            z-index: 1020;
            transition: background 0.3s;
        }
        .dark-mode .navbar {
            background: linear-gradient(90deg, var(--nav-start-dark), var(--nav-end-dark));
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); 
        }

        /* Teks dan Ikon di dalam Navbar */
        .navbar-brand .text-color, 
        .dropdown-toggle span {
            color: var(--navbar-text) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); 
        }
        .navbar-brand img {
            filter: drop-shadow(0 0 1px rgba(255, 255, 255, 0.8));
        }
        .navbar #darkModeToggle {
            color: var(--navbar-text) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
        }
        .navbar #darkModeToggle:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Card & Shadow Modern */
        .card {
            border: none;
            border-radius: 16px;
            background-color: var(--card-bg);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* --- Statistic Cards (Tetap Full Color) --- */
        .stat-card-bg { color: #fff !important; }
        .stat-card-1 { background-color: var(--color-pink); }
        .stat-card-2 { background-color: var(--color-green); }
        .stat-card-3 { background-color: var(--color-yellow); }
        .stat-card-4 { background-color: var(--genz-primary); }
        .stat-card-bg .big-number { color: #fff; }
        .stat-card-bg .text-muted-genz { color: rgba(255, 255, 255, 0.8) !important; font-weight: 500;}

        /* --- Menu Navigasi (Desktop Floating Icon) --- */
        /* Menyembunyikan menu ini di mobile (di bawah breakpoint lg) */
        @media (max-width: 991.98px) {
            .desktop-nav-icons {
                display: none !important;
            }
        }
        .nav-link-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 15px;
            margin: 0 8px;
            background-color: #fff;
            border-radius: 16px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease-in-out;
            color: var(--text-color);
            text-decoration: none;
            flex-grow: 1;
            max-width: 200px; 
            font-weight: 600;
        }
        .dark-mode .nav-link-icon {
            background-color: var(--card-bg);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        /* Warna Ikon Menu Individual (sama untuk desktop/mobile) */
        .nav-link-icon i { color: var(--genz-primary); font-size: 2.2rem; margin-bottom: 5px; transition: transform 0.3s; }
        #nav-pengajuan i { color: var(--color-pink); }
        #nav-riwayat i { color: var(--color-yellow); }
        #nav-admin i { color: var(--genz-secondary); }
        .nav-link-icon.active {
            background-color: var(--genz-primary);
            color: #fff !important;
            box-shadow: 0 6px 15px rgba(108, 92, 231, 0.5);
            transform: translateY(-2px);
        }
        .nav-link-icon.active i {
            color: #fff;
        }

        /* --- BOTTOM NAVIGATION (MOBILE ONLY) --- */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--card-bg); /* Menggunakan warna card BG untuk Bottom Nav */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1010;
            display: none; /* Default hidden */
        }
        /* Tampilkan hanya di mobile (di bawah breakpoint lg) */
        @media (max-width: 991.98px) {
            .bottom-nav {
                display: flex;
            }
        }

        .bottom-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            padding: 8px 5px;
            text-decoration: none;
            color: var(--muted-text);
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .bottom-nav-link i {
            font-size: 1.3rem;
            margin-bottom: 2px;
            color: var(--muted-text);
            transition: color 0.2s;
        }

        .bottom-nav-link.active,
        .bottom-nav-link:hover {
            color: var(--genz-primary);
        }
        .bottom-nav-link.active i {
            color: var(--genz-primary);
        }

        /* Warna Ikon Bottom Nav Saat Aktif (Mencocokkan warna asli) */
        #mobile-nav-dashboard.active i { color: var(--genz-primary); }
        #mobile-nav-pengajuan.active i { color: var(--color-pink); }
        #mobile-nav-riwayat.active i { color: var(--color-yellow); }
        #mobile-nav-admin.active i { color: var(--genz-secondary); }

        /* Style Tambahan */
        .big-number { font-size: 2.5rem; font-weight: 700; color: var(--genz-primary); }
        .text-muted-genz { color: var(--muted-text) !important; font-size: 0.85rem;}
        .avatar-sm { 
            width: 35px; 
            height: 35px; 
            object-fit: cover; 
            border: 2px solid var(--navbar-text); 
        }

        /* Footer Style */
        .app-footer {
            margin-top: 50px;
            padding: 20px 0;
            border-top: 1px solid var(--light-border);
            text-align: center;
            font-size: 0.8rem;
            color: var(--muted-text);
        }
    </style>
</head>
<body id="body">

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <img src="https://bualkawan.siakkab.go.id/logo-bualkawan2.png" alt="Bual Kawan Logo" height="30" class="me-2">
            <span class="text-color">Bual Kawan</span>
        </a>
        
        <div class="d-flex align-items-center ms-auto">
            <button class="btn btn-sm btn-outline-secondary border-0 me-3" id="darkModeToggle" title="Ganti Mode">
                <i class="bi bi-sun" id="modeIcon"></i>
            </button>

            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-md-inline me-2 text-color fw-semibold">Halo, Admin!</span>
                    <img src="https://i.pravatar.cc/150?img=68" class="rounded-circle avatar-sm" alt="Admin Avatar">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="pill" data-bs-target="#profil" onclick="setActiveNav('profil')"><i class="bi bi-person-circle me-2"></i> Profil Pribadi</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4 desktop-nav-icons">
            <div class="d-flex flex-wrap justify-content-center">
                <a href="dashboard.php" class="nav-link-icon active" id="nav-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                
                <a href="pengajuan.php" class="nav-link-icon" id="nav-pengajuan"><i class="bi bi-send-plus"></i> Pengajuan</a>
                
                <a href="riwayat.php" class="nav-link-icon" id="nav-riwayat"><i class="bi bi-clock-history"></i> Riwayat Tamu</a>
                
                <a href="admin.php" class="nav-link-icon" id="nav-admin"><i class="bi bi-people"></i> Admin</a>
            </div>
        </div>
    </div>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
            <h2 class="mb-4 fw-bold text-color">Statistik Kunjungan $Flash$ ✨</h2>
            
            <div class="row g-3 mb-4">
                <div class="col-lg-3 col-6">
                    <div class="card p-3 stat-card-bg stat-card-1">
                        <i class="bi bi-calendar-check fs-4 mb-2"></i>
                        <div class="big-number">45</div>
                        <div class="text-muted-genz">Total Tamu Hari Ini</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card p-3 stat-card-bg stat-card-2">
                        <i class="bi bi-graph-up fs-4 mb-2"></i>
                        <div class="big-number">+12%</div>
                        <div class="text-muted-genz">Kenaikan Kunjungan (vs $L$ Week)</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card p-3 stat-card-bg stat-card-3">
                        <i class="bi bi-hourglass-split fs-4 mb-2"></i>
                        <div class="big-number">15 Min</div>
                        <div class="text-muted-genz">Rata-rata Durasi Tamu</div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card p-3 stat-card-bg stat-card-4">
                        <i class="bi bi-people fs-4 mb-2"></i>
                        <div class="big-number">3.2K</div>
                        <div class="text-muted-genz">Total Tamu Sepanjang Masa</div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-color">Tren Kunjungan 7 Hari Terakhir</h5>
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card p-4 h-100">
                        <h5 class="fw-bold text-color">Distribusi Tujuan</h5>
                        <canvas id="donutChart"></canvas>
                        <div class="mt-3 text-center text-muted-genz">Data berdasarkan 500 kunjungan terakhir.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="profil" role="tabpanel">
            <h2 class="mb-4 fw-bold text-color">Profil Pribadi 👤</h2>
            <div class="card p-4 col-lg-6 mx-auto">
                <div class="text-center mb-4">
                    <img src="https://i.pravatar.cc/150?img=68" class="rounded-circle avatar-lg mb-2" alt="Foto Profil">
                    <h4 class="fw-bold mb-0 text-color">Admin Bual Kawan</h4>
                    <p class="text-muted-genz">Super Admin</p>
                </div>
                <form>
                    <div class="mb-3">
                        <label for="namaLengkap" class="form-label text-color">Nama Lengkap</label>
                        <input type="text" class="form-control" id="namaLengkap" value="Admin Bual Kawan" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-color">Email</label>
                        <input type="email" class="form-control" id="email" value="admin@bualkawan.id" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label text-color">Nomor Telepon</label>
                        <input type="tel" class="form-control" id="telepon" value="08123456789">
                    </div>
                    <button type="submit" class="btn btn-genz w-100 mt-3">Simpan Perubahan</button>
                    <button type="button" class="btn btn-outline-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Keluar / Log Out</button>
                </form>
            </div>
        </div>
        
    </div>
</div>

<footer class="app-footer">
    &copy; 2025 Bual Kawan - All rights reserved. | Made with <i class="bi bi-heart-fill text-danger"></i> by Tim Developer
</footer>

<div class="bottom-nav d-lg-none">
    <a href="dashboard.php" class="bottom-nav-link active" id="mobile-nav-dashboard">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>
    
    <a href="pengajuan.php" class="bottom-nav-link" id="mobile-nav-pengajuan">
        <i class="bi bi-send-plus"></i>
        Pengajuan
    </a>
    
    <a href="riwayat.php" class="bottom-nav-link" id="mobile-nav-riwayat">
        <i class="bi bi-clock-history"></i>
        Riwayat
    </a>
    
    <a href="admin.php" class="bottom-nav-link" id="mobile-nav-admin">
        <i class="bi bi-people"></i>
        Admin
    </a>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-body p-4">
        <div class="logout-icon-box mx-auto">
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <h5 class="fw-bold text-color mb-2">Yakin Ingin Keluar?</h5>
        <p class="text-muted-genz">Sesi Anda akan diakhiri. Anda perlu login kembali.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 p-3">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="login.php" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script>
    // --- JavaScript untuk Interaksi & Grafik ---

    // Fungsi untuk mengaktifkan tab (Hanya Profil)
    function setActiveNav(targetId) {
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
        const targetPane = document.getElementById(targetId);
        if (targetPane) {
            targetPane.classList.add('show', 'active');
        }
    }

    // --- Dark Mode Toggle Logic ---
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.getElementById('body');
    const modeIcon = document.getElementById('modeIcon');
    
    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            modeIcon.classList.remove('bi-sun');
            modeIcon.classList.add('bi-moon-stars');
            localStorage.setItem('theme', 'dark');
        } else {
            modeIcon.classList.remove('bi-moon-stars');
            modeIcon.classList.add('bi-sun');
            localStorage.setItem('theme', 'light');
        }
        
        // Re-initialize charts to update colors & re-apply dynamic styles
        initializeCharts();
        updateDynamicStyles();
    });

    // Fungsi untuk memperbarui style dinamis (misalnya border avatar)
    function updateDynamicStyles() {
        const isDarkMode = body.classList.contains('dark-mode');
        const navbarTextColor = getComputedStyle(document.documentElement).getPropertyValue('--navbar-text').trim();
        
        // Update border avatar
        const avatar = document.querySelector('.avatar-sm');
        if (avatar) {
            avatar.style.borderColor = navbarTextColor;
        }
    }

    // Cek preferensi tema saat memuat halaman
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        modeIcon.classList.remove('bi-sun');
        modeIcon.classList.add('bi-moon-stars');
    }
    
    // Panggil updateDynamicStyles saat DOM dimuat dan saat mode berubah
    document.addEventListener('DOMContentLoaded', updateDynamicStyles);
    
    // Fungsi untuk inisialisasi Chart.js
    function initializeCharts() {
        const isDarkMode = body.classList.contains('dark-mode');
        const textColor = isDarkMode ? '#f5f7fa' : '#212529';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const chartColors = ['#6c5ce7', '#ff6b81', '#1dd1a1', '#ff9f43', '#00cec9']; 

        // Hapus chart lama sebelum membuat yang baru
        if (window.lineChartInstance) window.lineChartInstance.destroy();
        if (window.donutChartInstance) window.donutChartInstance.destroy();

        // 1. Line Chart
        const lineCtx = document.getElementById('lineChart');
        const gradient = lineCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(108, 92, 231, 0.5)'); 
        gradient.addColorStop(1, 'rgba(108, 92, 231, 0)');  

        window.lineChartInstance = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                datasets: [{
                    label: 'Kunjungan',
                    data: [25, 40, 35, 55, 60, 45, 30],
                    borderColor: chartColors[0],
                    backgroundColor: gradient,
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: chartColors[0]
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    x: {
                         grid: { display: false },
                         ticks: { color: textColor }
                    }
                }
            }
        });
        
        // 2. Donut Chart
        const donutCtx = document.getElementById('donutChart');
        window.donutChartInstance = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Rapat Koordinasi', 'Pelatihan', 'Kerja Sama', 'Lainnya'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: [45, 25, 20, 10],
                    backgroundColor: chartColors.slice(0, 4),
                    hoverOffset: 8,
                    borderWidth: 4,
                    borderColor: isDarkMode ? '#1f1d2e' : '#ffffff' 
                }]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            color: textColor,
                            font: { size: 14 }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi Chart saat halaman dimuat
    document.addEventListener('DOMContentLoaded', initializeCharts);
    
</script>
</body>
</html>