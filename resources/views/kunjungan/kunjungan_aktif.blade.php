<x-breadcrumb :title="$title" />
<x-layout title="Daftar Kunjungan">
    {{-- Hapus script qrcode.js karena kita menggunakan QR code yang sudah dibuat di server --}}

    <div class="container py-3 py-md-5">
        <div class="card shadow-lg border-0">
            <div class="card-body p-3 p-md-4">
                
                <h2 class="mb-4 fw-bold fs-4">Daftar Kunjungan Aktif</h2>
                
                @if (session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {!! session('success') !!}</div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
                @endif

                {{-- Aksi Atas: Tambah Kunjungan & Pencarian --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('kunjungan.create') }}" class="btn btn-primary me-md-3">
                            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kunjungan
                        </a>
                        <button onclick="window.location.reload()" class="btn btn-outline-secondary" title="Refresh Data">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('kunjungan.index') }}" class="w-md-auto">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama / instansi..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                
                {{-- TABEL RESPONSIVE --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light">
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
                                    {{-- Tampilkan detail penting di mobile --}}
                                    <div class="d-md-none small text-muted">
                                        {{ $kunjungan->satuan_kerja }}<br>
                                        <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d-m-Y') }}
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $kunjungan->satuan_kerja }}</td>
                                <td class="d-none d-lg-table-cell">{{ $kunjungan->tujuan ?? 'Koordinasi' }}</td> 
                                <td class="d-none d-sm-table-cell">{{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d-m-Y') }}</td>
                                <td>
                                    {{-- TOMBOL TUNGGAL: Ikon Mata yang memicu Modal QR --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#qrModal"
                                        data-kunjungan-nama="{{ $kunjungan->nama_instansi }}"
                                        data-detail-link="{{ route('kunjungan.detail', $kunjungan->uid) }}"
                                        data-qr-url="{{ $kunjungan->qr_detail_url }}" 
                                        data-kunjungan-status="{{ $kunjungan->status }}"
                                        title="Tampilkan QR Code & Detail Kunjungan">
                                            <i class="bi bi-eye"></i> 
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
                    {{ $kunjunganAktif->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- ---------------------------------------------------------------- --}}
    {{-- MODAL QR CODE (HTML) --}}
    {{-- ---------------------------------------------------------------- --}}
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code Detail Kunjungan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Silakan scan kode di bawah untuk menuju halaman detail:</p>
                    <h6 id="kunjunganNamaDisplay" class="fw-bold mb-3 text-primary"></h6>
                    
                    {{-- DIV TEMPAT QR CODE AKAN DIMUAT DARI SERVER --}}
                    <div id="qrcode" class="d-flex justify-content-center mb-3">
                        <span class="text-muted">Memuat QR Code...</span>
                    </div> 
                    
                    {{-- Perbaikan: Menambahkan ID agar bisa diakses JS --}}
                    <a id="qrLinkDisplay" href="#" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-link-45deg"></i> Buka Halaman Detail Langsung
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ---------------------------------------------------------------- --}}
    {{-- JAVASCRIPT (MEMUAT QR CODE DAN MENGATUR LINK) --}}
    {{-- Tidak ada perubahan fungsional di JS karena sudah cukup responsif --}}
    {{-- ---------------------------------------------------------------- --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrModal = document.getElementById('qrModal');
            const qrcodeDiv = document.getElementById('qrcode');
            const qrLinkDisplay = document.getElementById('qrLinkDisplay'); 
            
            // Status yang valid untuk menampilkan QR Code
            const statusValid = ['disetujui', 'kunjungan']; 
            
            // DAFTAR PERAN YANG DIIZINKAN untuk melihat QR Code
            const authorizedRoles = ['admin', 'superadmin', 'operator']; 

            if (qrModal) {
                qrModal.addEventListener('show.bs.modal', function (event) {
                    
                    const button = event.relatedTarget; 
                    const kunjunganNama = button.getAttribute('data-kunjungan-nama');
                    const detailLink = button.getAttribute('data-detail-link'); 
                    const qrImageUrl = button.getAttribute('data-qr-url'); 
                    const kunjunganStatus = button.getAttribute('data-kunjungan-status');
                    
                    // PENTING: Ambil peran pengguna dari atribut data-user-role pada tombol
                    const userRole = button.getAttribute('data-user-role'); 

                    // 1. Atur Nama Instansi
                    document.getElementById('kunjunganNamaDisplay').textContent = kunjunganNama;

                    // 2. Atur Link Langsung
                    if (qrLinkDisplay) {
                        qrLinkDisplay.href = detailLink;
                        qrLinkDisplay.classList.remove('d-none');
                    }

                    // --- Pengecekan Otorisasi di JavaScript ---
                    if (userRole && authorizedRoles.includes(userRole.toLowerCase())) {
                        
                        // JIKA PERAN DIIZINKAN (admin, superadmin, operator)
                        if (qrImageUrl && statusValid.includes(kunjunganStatus.toLowerCase())) {
                            
                            // Tampilkan gambar QR
                            qrcodeDiv.innerHTML = <img src="${qrImageUrl}" alt="QR Code Kunjungan" style="width: 200px; height: 200px;">;
                            
                        } else {
                            // Tampilkan pesan status belum valid
                            qrcodeDiv.innerHTML = `<div class="alert alert-warning">
                                QR Code akan tersedia setelah disetujui Admin. Status saat ini: <strong>${kunjunganStatus.toUpperCase()}</strong>
                            </div>`;
                        }
                    } else {
                        // JIKA BUKAN PERAN YANG DIIZINKAN (guest/tamu)
                        // QR code tidak muncul, diganti dengan pesan peringatan
                        qrcodeDiv.innerHTML = `<div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Akses Dibatasi!</h5>
                            <p>Kode QR hanya dapat dilihat oleh pengguna yang sudah *Login* dengan peran *Operator, Admin, atau Superadmin*.</p>
                        </div>`;
                    }
                });
                
                qrModal.addEventListener('hidden.bs.modal', function () {
                    // Bersihkan saat modal ditutup
                    qrcodeDiv.innerHTML = '<span class="text-muted">Memuat QR Code...</span>';
                    document.getElementById('kunjunganNamaDisplay').textContent = '';
                    if (qrLinkDisplay) {
                        qrLinkDisplay.href = '#';
                    }
                });
            }
        });
    </script>
</x-layout>