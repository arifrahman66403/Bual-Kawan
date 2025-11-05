<x-layout title="Riwayat Tracking">

  <!-- Judul -->
  <h2 class="mb-4 fw-bold text-color">Riwayat Perubahan Status Kunjungan 🔄</h2>

  <!-- Card Utama -->
  <div class="card p-4">

    <!-- Header tabel -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
      <h5 class="fw-bold text-color mb-2 mb-md-0">Data Perubahan Terbaru</h5>
      <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-download"></i> Export Excel
        </button>
        <button class="btn btn-sm btn-genz">
          <i class="bi bi-clock-history"></i> Refresh
        </button>
      </div>
    </div>

    <!-- Tabel -->
    <div class="table-responsive">
      <table class="table table-hover align-middle table-borderless" id="dataRiwayat">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama Instansi</th>
            <th>Status</th>
            <th>Catatan</th>
            <th>Diperbarui Oleh</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trackings as $no => $t)
          <tr>
            <td>{{ $no + 1 }}</td>
            <td>
              <span class="fw-semibold text-color">{{ $t->pengunjung->nama_instansi ?? '-' }}</span>
            </td>
            <td>
              <span class="badge 
                @if($t->status=='pengajuan') bg-secondary 
                @elseif($t->status=='disetujui') bg-success 
                @elseif($t->status=='kunjungan') bg-info 
                @elseif($t->status=='selesai') bg-primary 
                @else bg-dark @endif">
                {{ ucfirst($t->status) }}
              </span>
            </td>
            <td>{{ $t->catatan ?? '-' }}</td>
            <td>{{ $t->createdBy->nama ?? 'System' }}</td>
            <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y H:i') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada riwayat perubahan status.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
      <!-- Links -->
    </div>

  </div>

</x-layout>
