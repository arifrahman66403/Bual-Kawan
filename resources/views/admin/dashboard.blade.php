<x-layout title="Admin Dashboard">
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">🛡️ Admin Dashboard</h1>
        <p>Akses Anda: Validasi dan Data Master. Fokus: Pengajuan Pengunjung.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <div class="h5">Pengajuan Baru: {{ $pengajuanCount ?? 0 }}</div>
                        <a href="{{ route('admin.pengajuan.index') }}" class="text-dark fw-bold">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.pengunjung.index') }}" class="btn btn-secondary">Data Master Pengunjung</a>
    </div>
</x-layout>
