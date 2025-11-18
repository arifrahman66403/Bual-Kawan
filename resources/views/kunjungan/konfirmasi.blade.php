<x-layout title="Konfirmasi Data Peserta">
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-5 text-center shadow-lg border-success border-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    
                    <h2 class="fw-bold text-success mt-3">Data Berhasil Dikirim!</h2>
                    
                    {{-- Tampilkan pesan sukses dari Controller --}}
                    @if (session('success'))
                        <p class="lead mt-3">{{ session('success') }}</p>
                    @endif

                    <hr>
                    
                    <p class="text-muted">
                        Terima kasih, **{{ $pengunjung->nama_perwakilan }}**! Data seluruh peserta rombongan dari **{{ $pengunjung->nama_instansi }}** untuk kunjungan pada tanggal 
                        **{{ Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('d F Y') }}** telah berhasil direkam.
                    </p>

                    <a href="/" class="btn btn-outline-secondary mt-4">
                        <i class="bi bi-house"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</x-layout>