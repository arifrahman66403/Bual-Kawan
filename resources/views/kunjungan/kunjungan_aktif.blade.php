@php
    // 1. Dapatkan peran pengguna saat ini. Jika belum login, set default ke 'guest'.
    $userRole = Auth::check() ? Auth::user()->role : 'guest';
    // 2. Dapatkan status aktif pengguna saat ini. Jika belum login, set default ke '0' (non-aktif).
    $userActive = Auth::check() ? Auth::user()->is_active : '0';
@endphp

<x-layout title="Daftar Kunjungan">
    
    <style>
        /* Definisi Variabel yang DIASUMSIKAN */
        :root {
            --card: #ffffff;
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            --accent: #4CAF50; 
            --accent-dark: #38761D; /* Hijau Tua */
            --muted: #6c757d;
        }

        /* ========================================= */
        /* ===== CSS DARI APLIKASI BUKU TAMU (disesuaikan) ===== */
        /* ========================================= */
        .page{padding-top:28px;}
        .card{background:var(--card);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow);border:1px solid rgba(0,0,0,0.04);margin-bottom:22px}
        .card h2{margin:0 0 12px;font-size:1.12rem;color:#102121}
        .controls{display:flex;align-items:center;gap:12px;justify-content:space-between;flex-wrap:wrap}
        /* Tombol Utama Hijau Solid */
        .btn{display:inline-flex;align-items:center;gap:8px;background:var(--accent-dark);color:#fff;border:0;padding:9px 14px;border-radius:10px;font-weight:800;cursor:pointer;box-shadow:0 6px 14px rgba(20,40,20,0.06)}
        /* Tombol Sekunder/Outline */
        .btn.secondary{background:transparent;color:var(--accent-dark);border:1px solid rgba(56,118,29,0.12);font-weight:700}
        .btn.icon-only{padding:8px 10px;border-radius:8px}
        .small{padding:6px 8px;font-size:0.92rem}
        .input { padding:10px;border-radius:10px;border:1px solid #e6e6e6;min-width:200px }
        .table-wrap{overflow:auto;margin-top:12px}
        table.app-table{width:100%;border-collapse:collapse;min-width:720px}
        table.app-table thead th{background: linear-gradient(180deg,var(--accent-dark),#2e6217);color:#fff;padding:12px 14px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.06);font-weight:700}
        table.app-table tbody td{padding:12px 14px;border-bottom:1px solid rgba(0,0,0,0.04);color:var(--muted);font-size:0.95rem}
        table.app-table tbody tr:nth-child(even) td{background: rgba(0,0,0,0.01)}
        .action-btn{background:transparent;border:1px solid rgba(0,0,0,0.06);padding:7px 10px;border-radius:8px;color:var(--muted);cursor:pointer;display:inline-flex;gap:8px;align-items:center}
        .form-area{display:none}
        .form-area.show{display:block;animation:fadeIn 260ms ease}

        /* --- PERBAIKAN PAGINATION STANDAR BOOTSTRAP --- */

        /* Container untuk centering */
        .d-flex.justify-content-center {
            margin-top: 20px;
        }

        /* 1. Mengatur tampilan dasar link pagination (angka, next, previous) */
        .page-item .page-link {
            border-radius: 8px; 
            margin: 0 4px;
            border: 1px solid rgba(0,0,0,0.08);
            color: var(--muted); 
            font-weight: 700;
            transition: all .16s;
            box-shadow: none;
            padding: 8px 12px;
            text-decoration: none; /* Tambahan agar link tidak bergaris */
        }

        /* 2. Mengatur tampilan link pagination yang AKTIF (HIJAU SOLID) */
        .page-item.active .page-link {
            background: linear-gradient(180deg, var(--accent-dark), #2f8d45) !important;
            border-color: var(--accent-dark) !important;
            color: #fff !important; 
            box-shadow: 0 8px 18px rgba(76,175,80,0.12) !important;
        }

        /* 3. Mengatur tampilan saat hover */
        .page-item .page-link:hover {
            transform: translateY(-3px); 
            background-color: transparent;
            color: #333;
        }

        /* 4. Mengatur link yang dinonaktifkan */
        .page-item.disabled .page-link {
            pointer-events: none;
            opacity: 0.5;
            background: transparent !important;
        }
        
        /* Mengatur Input Group agar menggunakan style .input */
        .input-group > .form-control {
            border-radius: 10px 0 0 10px !important;
            border-right: 0 !important;
        }
        .input-group > .btn-outline-secondary {
            border-radius: 0 10px 10px 0 !important;
        }
        /* Mengganti warna alert agar tidak terlalu kontras */
        .alert-success { background-color: #e6ffe6; border-color: #c3e6cb; color: #155724; }
        .alert-danger { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .alert-warning { background-color: #fff3cd; border-color: #ffeeba; color: #856404; }
    </style>

    <x-slot name="breadcrumb">
        <x-breadcrumb title="Buku Tamu">
            Selamat Datang — Buku Tamu Singgah
        </x-breadcrumb>
    </x-slot>

    <div class="page"> 

        @if (session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {!! session('success') !!}</div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
        @endif
        
        <div class="card" id="cardTable">
            
            <h2>Daftar Kunjungan Aktif</h2>
            
            <div class="controls">
                
                <div style="display:flex; gap:12px;">
                    <a href="{{ route('kunjungan.create') }}" class="btn">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Kunjungan
                    </a>
                    
                    <button onclick="window.location.reload()" class="btn icon-only secondary" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>

                <form method="GET" action="{{ route('kunjungan.index') }}" class="w-md-auto">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / instansi..." value="{{ request('search') }}" style="border-radius: 10px;">
                    </div>
                </form>
            </div>
            
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50px;">No</th>
                            <th scope="col">Instansi / PIC</th>
                            <th scope="col" class="d-none d-md-table-cell">Satuan Kerja</th>
                            <th scope="col" class="d-none d-lg-table-cell">Tujuan Kunjungan</th>
                            <th scope="col" class="d-none d-sm-table-cell" style="width: 120px;">Tanggal</th>
                            <th scope="col" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjunganAktif as $index => $kunjungan)
                        <tr>
                            <td>{{ $kunjunganAktif->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold">{{ $kunjungan->nama_instansi }}</span>
                                <div class="d-md-none small text-muted">
                                    {{ $kunjungan->satuan_kerja }}<br>
                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d-m-Y') }}
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $kunjungan->satuan_kerja }}</td>
                            <td class="d-none d-lg-table-cell">{{ $kunjungan->tujuan ?? 'Koordinasi' }}</td> 
                            <td class="d-none d-sm-table-cell">{{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d-m-Y') }}</td>
                            <td>
                                <button 
                                    type="button" 
                                    class="action-btn small" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#qrModal"
                                    data-kunjungan-nama="{{ $kunjungan->nama_instansi }}"
                                    data-detail-link="{{ route('kunjungan.qrcode', $kunjungan->uid) }}"
                                    data-qr-url="{{ $kunjungan->qr_detail_url }}" 
                                    data-kunjungan-status="{{ $kunjungan->status }}"
                                    data-user-role="{{ $userRole }}" 
                                    data-user-active="{{ $userActive ?? '0' }}" 
                                    title="Tampilkan QR Code & Detail Kunjungan">
                                    <i class="bi bi-eye"></i> Detail 
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada kunjungan aktif saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{-- Memanggil template pagination Bootstrap default --}}
                {{ $kunjunganAktif->links() }} 
            </div>

        </div> 
    </div>

    {{-- MODAL QR CODE (HTML) --}}
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code Detail Kunjungan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Silakan *scan* kode di bawah untuk menuju halaman detail:</p>
                    <h6 id="kunjunganNamaDisplay" class="fw-bold mb-3 text-primary"></h6>
                    
                    {{-- DIV TEMPAT QR CODE AKAN DIMUAT DARI SERVER/JS --}}
                    <div id="qrcode" class="d-flex justify-content-center mb-3">
                        <span class="text-muted">Memuat QR Code...</span>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrModal = document.getElementById('qrModal');
            const qrcodeDiv = document.getElementById('qrcode');
            
            const statusValid = ['disetujui', 'kunjungan']; 
            const authorizedRoles = ['admin', 'superadmin', 'operator']; 

            if (qrModal) {
                qrModal.addEventListener('show.bs.modal', function (event) {
                    
                    const button = event.relatedTarget; 
                    const kunjunganNama = button.getAttribute('data-kunjungan-nama');
                    const qrImageUrl = button.getAttribute('data-qr-url'); 
                    const kunjunganStatus = button.getAttribute('data-kunjungan-status');
                    
                    const userRole = button.getAttribute('data-user-role'); 
                    const userActive = button.getAttribute('data-user-active'); 
                    
                    const isActive = (userActive === '1'); 

                    document.getElementById('kunjunganNamaDisplay').textContent = kunjunganNama;

                    const isAuthorizedRole = userRole && authorizedRoles.includes(userRole.toLowerCase());
                    const isUserActive = isActive; 
                    
                    if (isAuthorizedRole && isUserActive) {
                        
                        if (qrImageUrl && statusValid.includes(kunjunganStatus.toLowerCase())) {
                            
                            qrcodeDiv.innerHTML = `<img src="${qrImageUrl}" alt="QR Code Kunjungan" style="width: 200px; height: 200px;">`;
                            
                        } else {
                            qrcodeDiv.innerHTML = `<div class="alert alert-warning">
                                QR Code akan tersedia setelah disetujui Admin. Status saat ini: <strong>${kunjunganStatus.toUpperCase()}</strong>
                            </div>`;
                        }
                    } else if (isAuthorizedRole && !isUserActive) {
                        qrcodeDiv.innerHTML = `<div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Akses Ditolak!</h5>
                            <p>Akun Anda berstatus **NONAKTIF**. Silakan hubungi Administrator untuk melihat QR Code.</p>
                        </div>`;
                    }
                    else {
                        qrcodeDiv.innerHTML = `<div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Akses Dibatasi!</h5>
                            <p>Kode QR hanya dapat dilihat oleh pengguna yang memiliki hak akses.</p>
                        </div>`;
                    }
                });
                
                qrModal.addEventListener('hidden.bs.modal', function () {
                    qrcodeDiv.innerHTML = '<span class="text-muted">Memuat QR Code...</span>';
                    document.getElementById('kunjunganNamaDisplay').textContent = '';
                });
            }
        });
    </script>
</x-layout>