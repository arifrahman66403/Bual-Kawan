<x-layout title="Form Tambah Kunjungan Baru">
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
                        
                        {{-- NIP (kis_peserta_kunjungan.nip) - Perwakilan dijadikan Peserta 0 --}}
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP (jika ada)" value="{{ old('nip') }}">
                        </div>
                        
                        {{-- Jabatan (kis_peserta_kunjungan.jabatan) - Perwakilan dijadikan Peserta 0 --}}
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
                
                {{-- ==================== BAGIAN BARU: PESERTA ROMBONGAN ==================== --}}
                <hr class="mt-5 mb-4">
                <h5 class="mb-3 fw-bold">Daftar Peserta Rombongan <small class="text-muted fw-normal">(Selain Perwakilan di atas)</small></h5>
                
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-primary" id="add-peserta-btn">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Peserta Rombongan
                    </button>
                </div>

                {{-- Container tempat baris peserta akan ditambahkan --}}
                <div id="peserta-container">
                    </div>
                
                {{-- Tombol Aksi --}}
                <hr class="mt-4 mb-3">
                <div class="d-flex justify-content-end gap-2">
                    {{-- Menggunakan route kunjungan.index yang merupakan Daftar Kunjungan Aktif --}}
                    <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-success btn-success-custom">
                        <i class="bi bi-save-fill me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>

{{-- ==================== TEMPLATE ROW PESERTA (Hidden HTML) ==================== --}}
{{-- Template ini disembunyikan dan akan di-clone oleh JavaScript --}}
<template id="peserta-row-template">
    <div class="row g-3 mb-3 peserta-item border-start border-3 ps-3 py-2 bg-light bg-opacity-50">
        
        {{-- Nama Peserta (col-md-2) --}}
        <div class="col-md-2">
            <label class="form-label small mb-1">Nama Peserta</label>
            <input type="text" class="form-control form-control-sm" name="peserta_nama[]" placeholder="Nama lengkap" required>
        </div>
        
        {{-- Jabatan (col-md-2) --}}
        <div class="col-md-2">
            <label class="form-label small mb-1">Jabatan</label>
            <input type="text" class="form-control form-control-sm" name="peserta_jabatan[]" placeholder="Contoh: Staf/Guru" required>
        </div>
        
        {{-- Kontak (col-md-2) --}}
        <div class="col-md-2">
            <label class="form-label small mb-1">No. WA/NIP (Opsional)</label>
            <input type="text" class="form-control form-control-sm" name="peserta_kontak[]" placeholder="08xx atau NIP">
        </div>
        
        {{-- EMAIL BARU (col-md-3) --}}
        <div class="col-md-3">
            <label class="form-label small mb-1">Email (Opsional)</label>
            <input type="email" class="form-control form-control-sm" name="peserta_email[]" placeholder="email@contoh.com">
        </div>
        
        {{-- FILE TTD dan Hapus (col-md-3) --}}
        <div class="col-md-3"> 
            <label class="form-label small mb-1">File TTD (JPG/PNG)</label>
            <input type="file" class="form-control form-control-sm mb-2" name="peserta_ttd[]" accept="image/jpeg,image/png">
            <button type="button" class="btn btn-sm btn-danger w-100 remove-peserta-btn">Hapus</button>
        </div>
    </div>
</template>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addButton = document.getElementById('add-peserta-btn');
        const container = document.getElementById('peserta-container');
        const template = document.getElementById('peserta-row-template');

        // Fungsi untuk menambahkan baris peserta
        function addPesertaRow() {
            // Kloning template dari DOM
            const clone = template.content.cloneNode(true);
            const newRow = clone.querySelector('.peserta-item');
            
            // Tambahkan event listener untuk tombol hapus pada baris baru
            newRow.querySelector('.remove-peserta-btn').addEventListener('click', function() {
                newRow.remove();
            });
            
            // Sisipkan baris baru ke dalam container
            container.appendChild(newRow);
        }

        // Tambahkan event listener ke tombol 'Tambah Peserta'
        addButton.addEventListener('click', addPesertaRow);

        // Opsi: Jika Anda ingin minimal ada 1 baris peserta default, aktifkan baris ini:
        // addPesertaRow(); 
    });
</script>