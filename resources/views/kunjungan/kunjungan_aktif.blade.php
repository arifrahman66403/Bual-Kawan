<x-breadcrumb :title="$title" />
<x-layout title="Daftar Kunjungan">
    {{-- Hapus script qrcode.js karena kita menggunakan QR code yang sudah dibuat di server --}}

    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                
                <h2 class="mb-4 fw-bold">Daftar Kunjungan Aktif</h2>
                
                @if (session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('kunjungan.create') }}" class="btn btn-success me-3">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kunjungan
                    </a>
                    
                    <button onclick="window.location.reload()" class="btn btn-outline-secondary me-auto" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>

                    <form method="GET" action="{{ route('kunjungan.index') }}">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama / instansi..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="card-header-custom">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Instansi</th>
                                <th scope="col">Satuan Kerja</th>
                                <th scope="col">Tujuan Kunjungan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kunjunganAktif as $index => $kunjungan)
                            <tr>
                                <td>{{ $kunjunganAktif->firstItem() + $index }}</td>
                                <td>{{ $kunjungan->nama_instansi }}</td>
                                <td>{{ $kunjungan->satuan_kerja }}</td>
                                <td>{{ $kunjungan->tujuan ?? 'Koordinasi' }}</td> 
                                <td>{{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d-m-Y') }}</td>
                                <td>
                                    {{-- TOMBOL TUNGGAL: Ikon Mata yang memicu Modal QR --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#qrModal"
                                        data-kunjungan-nama="{{ $kunjungan->nama_instansi }}"
                                        {{-- Perbaikan: Menggunakan data-detail-link untuk tombol Buka Langsung --}}
                                        data-detail-link="{{ route('kunjungan.detail', $kunjungan->uid) }}"
                                        {{-- data-qr-url dari Accessor Model --}}
                                        data-qr-url="{{ $kunjungan->qr_code_url }}"
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
                    <p>Silakan *scan* kode di bawah untuk menuju halaman detail:</p>
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
    {{-- ---------------------------------------------------------------- --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrModal = document.getElementById('qrModal');
            const qrcodeDiv = document.getElementById('qrcode');
            const qrLinkDisplay = document.getElementById('qrLinkDisplay'); // Ambil elemen link modal
            
            // Status yang valid untuk menampilkan QR Code
            const statusValid = ['disetujui', 'kunjungan']; 

            if (qrModal) {
                qrModal.addEventListener('show.bs.modal', function (event) {
                    
                    const button = event.relatedTarget; 
                    const kunjunganNama = button.getAttribute('data-kunjungan-nama');
                    const detailLink = button.getAttribute('data-detail-link'); // Ambil URL Detail Link
                    const qrImageUrl = button.getAttribute('data-qr-url'); // Ambil URL Gambar QR
                    const kunjunganStatus = button.getAttribute('data-kunjungan-status');

                    // 1. Atur Nama Instansi
                    document.getElementById('kunjunganNamaDisplay').textContent = kunjunganNama;

                    // 2. Atur Link Langsung
                    if (qrLinkDisplay) {
                        qrLinkDisplay.href = detailLink;
                        qrLinkDisplay.classList.remove('d-none'); // Pastikan link terlihat
                    }

                    // 3. Atur Tampilan Gambar QR
                    if (qrImageUrl && statusValid.includes(kunjunganStatus.toLowerCase())) {
                        
                        // Tampilkan gambar QR dari storage
                        qrcodeDiv.innerHTML = `<img src="${qrImageUrl}" alt="QR Code Kunjungan" style="width: 200px; height: 200px;">`;
                        
                    } else {
                        // Tampilkan pesan error/warning
                        qrcodeDiv.innerHTML = `<div class="alert alert-warning">
                                                    QR Code akan tersedia setelah disetujui Admin. Status saat ini: <strong>${kunjunganStatus.toUpperCase()}</strong>
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