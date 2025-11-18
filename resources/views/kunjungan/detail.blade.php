<x-breadcrumb :title="$title" />
<x-layout title="Detail Kunjungan">
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
                        <div class="detail-value">{{ \Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('Y-m-d') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="detail-label">File SPT:</div>
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

                    {{-- ✅ Tampilkan QR Code dari database --}}
                    <div class="qr-code-area mb-4">
                        @if ($pengunjung->qrCode && $pengunjung->qrCode->qr_code)
                            <img src="{{ asset($pengunjung->qrCode->qr_code) }}" 
                                alt="QR Code Kunjungan"
                                width="200"
                                height="200"
                                class="border rounded shadow-sm">
                            <p class="mt-2 text-muted small">
                                Berlaku hingga: 
                                {{ \Carbon\Carbon::parse($pengunjung->qrCode->berlaku_sampai)->format('d M Y H:i') }}
                            </p>
                        @else
                            <div class="alert alert-warning py-2">QR Code belum dibuat.</div>
                        @endif
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
                <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
            
            @endif
        </div>
    </div>
</x-layout>
