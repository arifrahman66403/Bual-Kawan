<x-layout title="Manajemen Pengunjung">
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">{{ $pageTitle ?? 'Manajemen Pengunjung' }}</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Instansi</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Perwakilan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengunjungList ?? [] as $pengunjung)
                            <tr>
                                <td>{{ $pengunjung->nama_instansi }}</td>
                                <td>{{ $pengunjung->tgl_kunjungan }}</td>
                                <td>{{ $pengunjung->nama_perwakilan }}</td>
                                <td>
                                    @if($pengunjung->status == 'pengajuan')
                                        <span class="badge bg-warning text-dark">{{ ucfirst($pengunjung->status) }}</span>
                                    @elseif($pengunjung->status == 'disetujui')
                                        <span class="badge bg-success">{{ ucfirst($pengunjung->status) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($pengunjung->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengunjung->status == 'pengajuan')
                                        <form action="{{ route('admin.pengajuan.approve', $pengunjung->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                    @endif
                                    <a href="#" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">Tidak ada data pengunjung saat ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $pengunjungList->links() }}
            </div>
        </div>
    </div>
</x-layout>