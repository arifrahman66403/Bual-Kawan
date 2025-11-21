<x-layout title="Status Kunjungan">
    {{-- Pastikan component x-layout Anda sudah terdefinisi --}}
    
    @php
        // Ambil status atau default ke 'info' jika tidak ada
        $status_type = $status ?? 'info';
        
        // Tentukan styling berdasarkan status
        $icon_class = 'bi-info-circle-fill text-primary';
        $title = 'Informasi Kunjungan';
        $border_class = 'border-primary';

        if ($status_type == 'expired') {
            $icon_class = 'bi-x-octagon-fill text-danger';
            $title = 'Link Kadaluarsa!';
            $border_class = 'border-danger';
        } elseif ($status_type == 'disetujui' || $status_type == 'kunjungan') {
            $icon_class = 'bi-check-circle-fill text-success';
            $title = 'Status Aktif';
            $border_class = 'border-success';
        } elseif ($status_type == 'ditolak' || $status_type == 'selesai') {
            $icon_class = 'bi-slash-circle-fill text-secondary';
            $title = 'Status Non-Aktif';
            $border_class = 'border-secondary';
        }
    @endphp

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-5 text-center shadow-lg border-bottom-0 border-3 {{ $border_class }}">
                    
                    {{-- Icon Status --}}
                    <i class="bi {{ $icon_class }}" style="font-size: 4rem;"></i>
                    
                    <h2 class="fw-bold mt-3" style="color: {{ $border_class == 'border-danger' ? '#dc3545' : ($border_class == 'border-success' ? '#198754' : '#0d6efd') }}">{{ $title }}</h2>
                    
                    <hr>
                    
                    {{-- Pesan dari Controller --}}
                    <p class="lead mt-3">{!! $message !!}</p>
                    
                    <p class="text-muted small mt-4">
                        Data Pengajuan: 
                        @if ($pengunjung)
                            {{ $pengunjung->nama_instansi }} (Perwakilan: {{ $pengunjung->nama_perwakilan }})
                        @else
                            (Data tidak tersedia)
                        @endif
                    </p>

                    <a href="{{ route('kunjungan.index')}}" class="btn btn-outline-secondary mt-4">
                        <i class="bi bi-house"></i> Kembali ke Buku Tamu
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>