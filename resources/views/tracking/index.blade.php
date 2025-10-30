<x-layout title="Tracking Kunjungan Pengunjung">
<div class="container py-5">
    
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 fw-bold">🚪 Tracking Kunjungan Pengunjung</h3>
        </div>

        <div class="card-body">
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <h5 class="card-title mb-3">Scan Kode QR</h5>
            <form action="{{ route('tracking.scan') }}" method="POST" class="mb-5">
                @csrf
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="kode_qr" class="form-control" placeholder="Masukkan Kode QR Pengunjung..." required>
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-qr-code-scan me-1"></i> Scan
                    </button>
                </div>
                @error('kode_qr')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </form>

            <h5 class="card-title mb-3">Riwayat Kunjungan Hari Ini</h5>
            
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nama Pengunjung</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Waktu Masuk</th>
                            <th scope="col">Waktu Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trackings as $track)
                        <tr>
                            <td class="fw-bold">{{ $track->pengunjung->nama }}</td>
                            
                            <td>
                                @if($track->status === 'masuk')
                                    <span class="badge bg-success text-white">Di Dalam</span>
                                @elseif($track->status === 'keluar')
                                    <span class="badge bg-secondary">Keluar</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($track->status) }}</span>
                                @endif
                            </td>
                            
                            <td>{{ $track->waktu_masuk ? \Carbon\Carbon::parse($track->waktu_masuk)->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $track->waktu_keluar ? \Carbon\Carbon::parse($track->waktu_keluar)->format('d/m/Y H:i:s') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data kunjungan hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- <div class="mt-3">
                {{ $trackings->links() }}
            </div> --}}

        </div>
    </div>
</div>
</x-layout>