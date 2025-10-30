<x-layout title="Dashboard Operator">
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">Scan 📲 Kunjungan</h1>
        
        @if (session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
        @endif

        <div class="card shadow-lg mb-5">
            <div class="card-body text-center p-5">
                <i class="bi bi-qr-code-scan display-1 text-primary mb-4"></i>
                <h4 class="card-title mb-4">Arahkan Scanner ke Kode QR Pengunjung</h4>
                
                <form action="{{ route('operator.scan') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-lg mx-auto" style="max-width: 500px;">
                        <input type="text" name="kode_qr" class="form-control" placeholder="Masukkan Kode QR..." autofocus required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-send-fill me-1"></i> Proses Check-in/out
                        </button>
                    </div>
                    @error('kode_qr')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>

            </div>
        </div>
        
        <a href="{{ route('operator.riwayat') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-task me-2"></i> Lihat Riwayat Scan Hari Ini
        </a>
    </div>
</x-layout>