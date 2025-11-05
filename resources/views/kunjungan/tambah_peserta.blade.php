<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta Rombongan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .form-card { background-color: white; border-radius: 1rem; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="form-card shadow-lg mx-auto p-4" style="max-width: 700px;">
        
        <h3 class="mb-3 fw-bold">Tambah Peserta Rombongan</h3>
        <p class="text-muted">
            Isi formulir di bawah ini untuk menambahkan anggota rombongan baru ke pengajuan kunjungan instansi: 
            **{{ $pengunjung->nama_instansi ?? 'N/A' }}** pada tanggal **{{ \Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('Y-m-d') }}**.
        </p>
        <hr>

        {{-- Menampilkan pesan error atau sukses --}}
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-x-octagon-fill"></i> {{ session('error') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                Gagal menyimpan data. Silakan periksa kembali kolom yang diisi.
            </div>
        @endif

        {{-- Form action menuju route 'peserta.store' dengan parameter UID pengunjung --}}
        <form action="{{ route('peserta.store', $pengunjung->uid) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap Peserta <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="nip" class="form-label">NIP (Opsional)</label>
                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}">
                @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="wa" class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('wa') is-invalid @enderror" id="wa" name="wa" value="{{ old('wa') }}" required>
                @error('wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <hr>
            <div class="d-flex justify-content-end gap-2">
                {{-- Kembali ke halaman detail --}}
                <a href="{{ route('kunjungan.detail', $pengunjung->uid) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Kunjungan
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambahkan Peserta
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>