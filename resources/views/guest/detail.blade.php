<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kunjungan - {{ $pengunjung->nama_instansi ?? 'Loading' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .detail-card {
            background-color: white;
            border-radius: 1rem;
        }
        .detail-label {
            font-weight: 500;
            color: #6c757d; /* Abu-abu */
        }
        .detail-value {
            font-weight: bold;
            color: #212529;
            margin-bottom: 0.8rem;
        }
        .section-title {
            color: #38761d;
            font-weight: bold;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="detail-card shadow-lg mx-auto p-5" style="max-width: 1000px;">
        
        <h2 class="mb-4 fw-bold">Detail Laporan Kunjungan</h2>
        
        @if (!isset($pengunjung))
            <div class="alert alert-warning text-center">Data pengunjung tidak ditemukan.</div>
        @else
        
        <div class="row">
            
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
                    <div class="detail-label">Tanggal Kunjungan:</div>
                    {{-- Format tanggal agar lebih rapi --}}
                    <div class="detail-value">{{ \Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('Y-m-d') }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="detail-label">File SPT:</div>
                    {{-- Cek apakah relasi dokumen ada dan file_spt tersedia --}}
                    <div class="detail-value">
                        @if ($pengunjung->dokumen && $pengunjung->dokumen->file_spt)
                            <a href="{{ Storage::url($pengunjung->dokumen->file_spt) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Lihat SPT
                            </a>
                        @else
                            <span class="text-muted">Tidak ada file SPT</span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- ==================== DATA PERWAKILAN ==================== --}}
            <div class="col-md-6 ps-md-5">
                <h4 class="section-title">Data Perwakilan</h4>
                
                <div class="mb-3">
                    <div class="detail-label">Nama:</div>
                    <div class="detail-value">{{ $pengunjung->nama_perwakilan ?? '-' }}</div>
                </div>
                
                {{-- Ambil data NIP, Jabatan dari Peserta Kunjungan (asumsi perwakilan adalah peserta pertama) --}}
                @php
                    $perwakilanPeserta = $pengunjung->peserta->first();
                @endphp
                
                <div class="mb-3">
                    <div class="detail-label">NIP:</div>
                    <div class="detail-value">{{ $perwakilanPeserta->nip ?? '-' }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="detail-label">Jabatan:</div>
                    <div class="detail-value">{{ $perwakilanPeserta->jabatan ?? '-' }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $pengunjung->email_perwakilan ?? '-' }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="detail-label">WhatsApp:</div>
                    <div class="detail-value">{{ $pengunjung->wa_perwakilan ?? '-' }}</div>
                </div>
            </div>
        </div>
        
        <hr class="mt-4 mb-4">

        {{-- ==================== QR CODE & ROMBONGAN ==================== --}}
        <div class="row">
            <div class="col-md-4 text-center border-end">
                <h4 class="section-title mt-0">QR Code (untuk absen)</h4>
                
                {{-- Tempat menaruh QR Code yang di-generate --}}
                <div class="qr-code-area mb-4">
                    {{-- Asumsi Anda menggunakan library untuk generate QR Code dari $pengunjung->kode_qr --}}
                    {{-- Contoh menggunakan URL: --}}
                    {{-- <img src="{{ route('qrcode.generate', $pengunjung->kode_qr) }}" alt="QR Code"> --}}
                    <div style="width: 150px; height: 150px; background-color: #ddd; margin: auto;"></div>
                    <p class="mt-2 text-muted small">Kode: {{ $pengunjung->kode_qr }}</p>
                </div>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengunjung->peserta as $index => $peserta)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $peserta->nama }}</td>
                                <td>{{ $peserta->nip ?? '-' }}</td>
                                <td>{{ $peserta->jabatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada peserta rombongan yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="text-end mt-4">
             <a href="{{ route('guest.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
        
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>