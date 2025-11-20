<x-layout-admin title="Detail Pengajuan">
    <div class="container-fluid p-4">
        <a href="{{ route('admin.pengajuan') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali</a>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="mb-3">Informasi Pengajuan</h4>
                        <table class="table table-borderless">
                            <tr><th>UID</th><td>{{ $pengunjung->uid }}</td></tr>
                            <tr><th>Kode Daerah</th><td>{{ $pengunjung->kode_daerah }}</td></tr>
                            <tr><th>Nama Instansi</th><td>{{ $pengunjung->nama_instansi }}</td></tr>
                            <tr><th>Satuan Kerja</th><td>{{ $pengunjung->satuan_kerja }}</td></tr>
                            <tr><th>Tujuan</th><td>{{ $pengunjung->tujuan }}</td></tr>
                            <tr><th>Tanggal Kunjungan</th><td>{{ \Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('Y-m-d') }}</td></tr>
                            <tr><th>Status</th><td><span class="badge bg-info text-dark">{{ $pengunjung->status }}</span></td></tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Perwakilan</h5>
                        <p><strong>{{ $pengunjung->nama_perwakilan }}</strong> — {{ $pengunjung->jabatan ?? '-' }}<br>
                        Email: {{ $pengunjung->email_perwakilan ?? '-' }} — WA: {{ $pengunjung->wa_perwakilan ?? '-' }}<br>
                        NIP: {{ $pengunjung->nip ?? '-' }}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Dokumen / File</h5>
                        
                        {{-- Cek apakah objek dokumen ada DAN memiliki path file SPT --}}
                        @if ($pengunjung->dokumen && $pengunjung->dokumen->file_spt)
                            
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    {{-- Langsung akses objek dokumen --}}
                                    <a href="{{ Storage::url($pengunjung->dokumen->file_spt) }}" target="_blank" class="fw-semibold">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Surat Perintah Tugas (SPT)
                                    </a>
                                    <small class="text-muted d-block">
                                        Diupload: {{ $pengunjung->dokumen->created_at ? $pengunjung->dokumen->created_at->format('Y-m-d H:i') : '-' }}
                                    </small>
                                </li>
                            </ul>
                        @else
                            {{-- Jika objek dokumen tidak ada atau file_spt kosong --}}
                            <div class="text-muted">
                                <i class="bi bi-x-circle-fill text-danger me-1"></i> Belum ada dokumen SPT yang terlampir.
                            </div>
                        @endif
                        
                    </div>
                </div>

                 <div class="card mb-3">
                     <div class="card-body">
                         <h5>Peserta Rombongan</h5>
                        @if ($pengunjung->peserta && is_iterable($pengunjung->peserta) && count($pengunjung->peserta) > 0)
                             <table class="table table-sm">
                                 <thead>
                                     <tr><th>No</th><th>Nama</th><th>Jabatan</th><th>Nip</th></tr>
                                 </thead>
                                 <tbody>
                                     @foreach($pengunjung->peserta as $i => $peserta)
                                        @if($peserta && is_object($peserta))
                                         <tr>
                                             <td>{{ $loop->iteration }}</td>
                                            <td>{{ $peserta->nama ?? '-' }}</td>
                                            <td>{{ $peserta->jabatan ?? '-' }}</td>
                                            <td>{{ $peserta->nip ?? '-' }}</td>
                                         </tr>
                                         @endif
                                     @endforeach
                                 </tbody>
                             </table>
                         @else
                             <div class="text-muted">Belum ada peserta terdaftar.</div>
                         @endif
                     </div>
                 </div>

                 <div class="card mb-3">
                     <div class="card-body">
                         <h5>Riwayat Tracking</h5>
                        @if ($pengunjung->tracking && is_iterable($pengunjung->tracking) && count($pengunjung->tracking) > 0)
                             <ul class="list-group">
                                @foreach($pengunjung->tracking as $track)
                                    @if($track && is_object($track))
                                     <li class="list-group-item">
                                        <strong>{{ $track->status ?? '-' }}</strong>
                                        <div class="small text-muted">oleh: {{ $track->createdBy && $track->createdBy->name ? $track->createdBy->name : 'System' }} — {{ $track->created_at ? $track->created_at->format('Y-m-d H:i') : '-' }}</div>
                                         @if(!empty($track->catatan))
                                             <div class="mt-1">{{ $track->catatan }}</div>
                                         @endif
+                                    @endif
                                     </li>
                                 @endforeach
                             </ul>
                         @else
                             <div class="text-muted">Belum ada riwayat tracking.</div>
                         @endif
                     </div>
                 </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <h5>QR Code</h5>
                        @if($pengunjung->qrCode && !empty($pengunjung->qrCode->qr_scan_path))
                            <img src="{{ Storage::url($pengunjung->qrCode->qr_scan_path) }}" alt="QR Code" class="img-fluid mb-2" style="max-width:220px;">
                            <div class="d-grid gap-2">
                                <a href="{{ Storage::url($pengunjung->qrCode->qr_scan_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">Buka Gambar QR</a>
                            </div>
                            <div class="small text-muted mt-2">
                                Berlaku mulai: {{ $pengunjung->qrCode->berlaku_mulai ? \Carbon\Carbon::parse($pengunjung->qrCode->berlaku_mulai)->format('Y-m-d H:i') : '-' }}<br>
                                Berlaku sampai: {{ $pengunjung->qrCode->berlaku_sampai ? \Carbon\Carbon::parse($pengunjung->qrCode->berlaku_sampai)->format('Y-m-d H:i') : '-' }}
                            </div>
                        @else
                            <div class="text-muted">Belum ada QR Code</div>
                        @endif
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5>Aksi Admin</h5>

                        <form action="{{ route('admin.pengajuan.status', $pengunjung->uid) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Ubah Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pengajuan" {{ $pengunjung->status === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                    <option value="disetujui" {{ $pengunjung->status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="selesai" {{ $pengunjung->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout-admin>