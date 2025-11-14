<x-breadcrumb />
<x-layout title="Buku Tamu Singgah">
    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                
                <h2 class="mb-4 fw-bold">Daftar Kunjungan Aktif</h2>
                
                @if (session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('kunjungan.create') }}" class="btn btn-success me-3">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kunjungan
                    </a>
                    
                    <button onclick="window.location.reload()" class="btn btn-outline-secondary me-auto" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>

                    <form method="GET" action="{{ route('kunjungan.index') }}">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama / instansi..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="card-header-custom">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama (Perwakilan)</th>
                                <th scope="col">Instansi</th>
                                <th scope="col">Tujuan Kunjungan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kunjunganAktif as $index => $kunjungan)
                            <tr>
                                <td>{{ $kunjunganAktif->firstItem() + $index }}</td>
                                <td>{{ $kunjungan->nama_perwakilan }}</td>
                                <td>{{ $kunjungan->nama_instansi }}</td>
                                <td>{{ $kunjungan->satuan_kerja ?? 'Koordinasi' }}</td> 
                                <td>{{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('kunjungan.detail', $kunjungan->uid) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada kunjungan aktif saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $kunjunganAktif->links() }}
                </div>

            </div>
        </div>
    </div>
</x-layout>
