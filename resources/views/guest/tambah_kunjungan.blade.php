<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Daftar Kunjungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-card {
            background-color: white;
            border-radius: 1rem;
        }
        .input-group-label {
            font-weight: bold;
            color: #1f500a; /* Hijau gelap */
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
        }
        .btn-success-custom {
            background-color: #38761d; /* Warna hijau button */
            border-color: #38761d;
        }
        .btn-success-custom:hover {
            background-color: #2b5c17;
            border-color: #2b5c17;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="form-card shadow-lg mx-auto p-4" style="max-width: 1000px;">
        
        <h3 class="mb-4 fw-bold">Form Daftar Kunjungan</h3>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Form action menuju route yang sudah kita buat --}}
        <form action="{{ route('kunjungan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                
                {{-- ==================== KOLOM KIRI: DATA INSTANSI & DOKUMEN ==================== --}}
                <div class="col-md-6 border-end pe-md-4">
                    <h5 class="mt-2 mb-4 input-group-label">Data Instansi</h5>
                    
                    {{-- Asal Daerah (kis_pengunjung.kode_daerah) --}}
                    <div class="mb-3">
                        <label for="kode_daerah" class="form-label">Asal Daerah</label>
                        <input type="text" class="form-control" id="kode_daerah" name="kode_daerah" placeholder="Contoh: Riau" value="{{ old('kode_daerah') }}">
                    </div>
                    
                    {{-- Nama Instansi (kis_pengunjung.nama_instansi) --}}
                    <div class="mb-3">
                        <label for="nama_instansi" class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" placeholder="Nama Instansi" value="{{ old('nama_instansi') }}">
                    </div>
                    
                    {{-- Satuan Kerja (kis_pengunjung.satuan_kerja) --}}
                    <div class="mb-3">
                        <label for="satuan_kerja" class="form-label">Satuan Kerja</label>
                        <input type="text" class="form-control" id="satuan_kerja" name="satuan_kerja" placeholder="Contoh: Diskominfo" value="{{ old('satuan_kerja') }}">
                    </div>

                    {{-- Tujuan (kis_pengunjung.tujuan) --}}
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan</label>
                        <input type="text" class="form-control" id="tujuan" name="tujuan" placeholder="Tujuan kunjungan" value="{{ old('tujuan') }}">
                    </div>

                    {{-- Tanggal Kunjungan (kis_pengunjung.tgl_kunjungan) --}}
                    <div class="mb-3">
                        <label for="tgl_kunjungan" class="form-label">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" value="{{ old('tgl_kunjungan') }}" placeholder="dd / mm / yyyy">
                    </div>
                    
                    {{-- File SPT (kis_dokumen.file_spt) --}}
                    <div class="mb-3">
                        <label for="file_spt" class="form-label">File SPT (PDF)</label>
                        <input class="form-control @error('file_spt') is-invalid @enderror" type="file" id="file_spt" name="file_spt" accept=".pdf">
                    </div>

                </div>
                
                {{-- ==================== KOLOM KANAN: DATA PERWAKILAN & KONTAK ==================== --}}
                <div class="col-md-6 ps-md-4">
                    <h5 class="mt-2 mb-4 input-group-label">Data Perwakilan</h5>
                    
                    {{-- Nama Perwakilan (kis_pengunjung.nama_perwakilan) --}}
                    <div class="mb-3">
                        <label for="nama_perwakilan" class="form-label">Nama Perwakilan</label>
                        <input type="text" class="form-control" id="nama_perwakilan" name="nama_perwakilan" placeholder="Nama lengkap" value="{{ old('nama_perwakilan') }}">
                    </div>
                    
                    {{-- NIP (kis_peserta_kunjungan.nip) --}}
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP (jika ada)" value="{{ old('nip') }}">
                    </div>
                    
                    {{-- Jabatan (kis_peserta_kunjungan.jabatan) --}}
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" value="{{ old('jabatan') }}">
                    </div>
                    
                    {{-- Email (kis_pengunjung.email_perwakilan) --}}
                    <div class="mb-3">
                        <label for="email_perwakilan" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_perwakilan" name="email_perwakilan" placeholder="email@contoh.go.id" value="{{ old('email_perwakilan') }}">
                    </div>
                    
                    {{-- No. WhatsApp (kis_pengunjung.wa_perwakilan) --}}
                    <div class="mb-3">
                        <label for="wa_perwakilan" class="form-label">No. WhatsApp</label>
                        <input type="text" class="form-control" id="wa_perwakilan" name="wa_perwakilan" placeholder="08xx..." value="{{ old('wa_perwakilan') }}">
                    </div>

                </div>
            </div>
            
            {{-- Tombol Aksi --}}
            <hr class="mt-4 mb-3">
            <div class="d-flex justify-content-end gap-2">
                {{-- Menggunakan route guest.index yang merupakan Daftar Kunjungan Aktif --}}
                <a href="{{ route('guest.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-success btn-success-custom">
                    <i class="bi bi-save-fill me-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>