<x-breadcrumb :title="$title" />
<x-layout title="Detail Kunjungan">
    <div class="container py-5">
        <div class="detail-card shadow-lg mx-auto p-5" style="max-width: 1000px;">
            
            <h2 class="mb-4 fw-bold">Detail Laporan Kunjungan</h2>
            
            {{-- Pesan Sukses/Error dari Controller --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if (!isset($pengunjung))
                <div class="alert alert-warning text-center">Data pengunjung tidak ditemukan.</div>
            @else
            
            <div class="row">
                
                {{-- HILANGKAN BLOK @php LAMA YANG MENGGUNAKAN is_perwakilan di View --}}
                
                {{-- ==================== DATA INSTANSI ==================== --}}
                <div class="col-md-6 border-end pe-md-5">
                    <h4 class="section-title">Data Instansi</h4>
                    
                    <div class="mb-3">
                        <div class="detail-label">Asal Daerah:</div>
                        <div class="detail-value">{{ $pengunjung->kode_daerah ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Nama Instansi:</div>
                        <div class="detail-value">{{ $pengunjung->nama_instansi ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Satuan Kerja:</div>
                        <div class="detail-value">{{ $pengunjung->satuan_kerja ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Tujuan Kunjungan:</div>
                        <div class="detail-value">{{ $pengunjung->tujuan ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Tanggal Kunjangan:</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('Y-m-d') }}</div>
                    </div>
                    
                    {{-- Blok untuk menampilkan status SPT dan tombol upload --}}
                    <div class="mb-3">
                        <div class="detail-label fw-semibold">File Surat Perintah Tugas (SPT):</div>
                        <div class="detail-value mt-2">
                            @if ($pengunjung->dokumen && $pengunjung->dokumen->file_spt)
                                {{-- KONDISI 1: File Sudah Ada --}}
                                <a href="{{ Storage::url($pengunjung->dokumen->file_spt) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Lihat SPT
                                </a>
                                
                                {{-- Tombol untuk Ganti File (Gunakan Modal) --}}
                                <button type="button" class="btn btn-sm btn-outline-warning ms-2" 
                                        data-bs-toggle="modal" data-bs-target="#uploadSptModal">
                                    <i class="bi bi-arrow-repeat me-1"></i> Ganti File
                                </button>

                            @else
                                {{-- KONDISI 2: File Belum Ada --}}
                                <span class="text-muted me-3">Tidak ada file SPT yang terlampir.</span>
                                
                                {{-- Tombol untuk memicu Modal Upload --}}
                                <button type="button" class="btn btn-sm btn-genz" 
                                        data-bs-toggle="modal" data-bs-target="#uploadSptModal">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> Lampirkan File SPT
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- ==================== DATA PERWAKILAN ==================== --}}
                {{-- Menggunakan variabel $perwakilanPeserta yang dikirim dari Controller --}}
                <div class="col-md-6 ps-md-5">
                    <h4 class="section-title">Data Perwakilan</h4>
                    
                    <div class="mb-3">
                        <div class="detail-label">Nama:</div>
                        {{-- Menggunakan data dari KisPengunjung (diakses via $perwakilanPeserta) --}}
                        <div class="detail-value">{{ $perwakilanPeserta->nama ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Jabatan:</div>
                        {{-- Menggunakan data dari KisPengunjung (diakses via $perwakilanPeserta) --}}
                        <div class="detail-value">{{ $perwakilanPeserta->jabatan ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">Email:</div>
                        {{-- Menggunakan data dari KisPengunjung (diakses via $perwakilanPeserta) --}}
                        <div class="detail-value">{{ $perwakilanPeserta->email ?? '-' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">WhatsApp:</div>
                        {{-- Menggunakan data dari KisPengunjung (diakses via $perwakilanPeserta) --}}
                        <div class="detail-value">{{ $perwakilanPeserta->wa ?? '-' }}</div>
                    </div>
                </div>
            </div>
            
            <hr class="mt-4 mb-4">

            {{-- ==================== QR CODE & ROMBONGAN ==================== --}}
            <div class="row">
                <div class="col-md-4 text-center border-end">
                    <h4 class="section-title mt-0">QR Code (untuk absen)</h4>

                    {{-- PERMINTAAN 1: Hilangkan QR Code jika status 'selesai' --}}
                    @if ($pengunjung->status === 'selesai')
                        <div class="alert alert-secondary mt-3">
                            <i class="bi bi-check-circle-fill"></i> Kunjungan telah **Selesai**.
                        </div>
                    @else
                        {{-- Tampilkan QR Code dari database --}}
                        <div class="qr-code-area mb-3">
                            @if ($pengunjung->qrCode && $pengunjung->qrCode->qr_scan_path)
                                
                                {{-- 1. Tampilkan Gambar QR Code --}}
                                <img src="{{ Storage::url($pengunjung->qrCode->qr_scan_path) }}" 
                                    alt="QR Code Kunjungan"
                                    width="200"
                                    height="200"
                                    class="border rounded shadow-sm">
                                
                                {{-- 2. Tampilkan Detail QR --}}
                                <p class="mt-2 text-muted small">
                                    **QR Berisi Link Input Peserta Rombongan**
                                    <br>Berlaku hingga: 
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($pengunjung->qrCode->berlaku_sampai)->format('d M Y H:i') }}</span>
                                </p>

                                {{-- 3. Tombol/Link Input Peserta --}}
                                <a href="{{ route('pengunjung.scan', $pengunjung->uid) }}" 
                                target="_blank" 
                                class="btn btn-sm btn-outline-genz mt-2">
                                    <i class="bi bi-link-45deg"></i> Input Peserta via Link
                                </a>
                                
                            @else
                                <div class="alert alert-warning py-2">
                                    QR Code belum dibuat. silahkan hubungi admin untuk pembuatan QR Code.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="col-md-8">
                    <h4 class="section-title mt-0">Rombongan</h4>
                    <p class="fw-bold">Daftar peserta:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jabatan</th>
                                    {{-- Kolom Tambahan jika diperlukan --}}
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Menggunakan variabel $anggotaRombongan yang sudah difilter di Controller --}}
                                @forelse ($anggotaRombongan as $index => $peserta)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $peserta->nama }}</td>
                                        <td>{{ $peserta->nip ?? '-' }}</td>
                                        <td>{{ $peserta->jabatan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Tidak ada peserta rombongan yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-4">
                <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
            
            @endif
        </div>
    </div>
    
    {{-- MODAL UPLOAD SPT (Tetap di sini) --}}
    @if (isset($pengunjung))
    <div class="modal fade" id="uploadSptModal" tabindex="-1" aria-labelledby="uploadSptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="uploadSptModalLabel">
                        <i class="bi bi-file-earmark-arrow-up text-genz"></i> Unggah Dokumen SPT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('kunjungan.upload.spt', $pengunjung->uid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="modal-body">
                        <div class="alert alert-info small" role="alert">
                            File wajib berformat **PDF** dan ukuran maksimum **2MB**.
                        </div>
                        
                        <label for="file_spt_upload" class="form-label fw-semibold">Pilih File Surat Perintah Tugas (SPT)</label>
                        <input class="form-control" type="file" id="file_spt_upload" name="file_spt" accept="application/pdf" required>

                        @error('file_spt')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-genz">
                            <i class="bi bi-upload me-1"></i> Unggah & Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-layout>