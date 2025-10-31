<x-layout title="Verifikasi Pengunjung">
      <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
        <h2 class="mb-4 fw-bold text-color">Daftar Pengajuan Kunjungan 📋</h2>

        <!-- Filter -->
        <div class="card p-3 mb-4 filter-status-box">
          <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold text-color me-2 d-none d-md-inline">Filter Status:</span>
            <button class="btn btn-sm btn-genz active" data-filter="Semua">
              <i class="bi bi-list-ul"></i> Semua ({{ $pengunjungs->count() }})
            </button>
            <button class="btn btn-sm btn-outline-warning text-dark" data-filter="Menunggu">
              <i class="bi bi-hourglass"></i> Menunggu ({{ $pengunjungs->where('status','pengajuan')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-success" data-filter="Disetujui">
              <i class="bi bi-check-circle"></i> Disetujui ({{ $pengunjungs->where('status','disetujui')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-danger" data-filter="Ditolak">
              <i class="bi bi-x-circle"></i> Ditolak ({{ $pengunjungs->where('status','ditolak')->count() }})
            </button>
          </div>
        </div>

        <!-- Tabel -->
        <div class="card p-4">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h5 class="fw-bold text-color mb-2 mb-md-0">Data Pengajuan Terbaru</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Export Excel</button>
              <button class="btn btn-sm btn-genz"><i class="bi bi-plus-circle"></i> Tambah Manual</button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle table-borderless pengajuan-table" id="dataPengajuan">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Pengaju / Instansi</th>
                  <th class="d-none d-md-table-cell">Tujuan Kunjungan</th>
                  <th>Tanggal & Waktu</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pengunjungs as $no => $p)
                <tr data-status="{{ ucfirst($p->status) }}">
                  <td>{{ $no+1 }}</td>
                  <td>
                    <span class="fw-semibold text-color">{{ $p->nama_perwakilan }}</span>
                    <div class="text-muted-genz">{{ $p->nama_instansi }}</div>
                  </td>
                  <td class="d-none d-md-table-cell">{{ $p->tujuan }}</td>
                  <td>{{ \Carbon\Carbon::parse($p->tgl_kunjungan)->format('d M Y') }}</td>
                  <td>
                    <span class="badge 
                      @if($p->status=='pengajuan') bg-secondary 
                      @elseif($p->status=='disetujui') bg-success 
                      @elseif($p->status=='kunjungan') bg-info 
                      @elseif($p->status=='selesai') bg-primary
                      @else bg-dark @endif">
                      {{ ucfirst($p->status) }}
                    </span>
                  </td>
                  <td class="text-nowrap">
                    <!-- Tombol Detail -->
                    <button class="btn btn-sm btn-genz" title="Lihat Detail"><i class="bi bi-eye"></i></button>

                    <!-- Tombol Setujui -->
                    <form action="{{ route('pengunjung.status', $p->uid) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="status" value="disetujui">
                      <button type="submit" class="btn btn-sm btn-success ms-1" title="Setujui">
                        <i class="bi bi-check2-circle"></i> Terima
                      </button>
                    </form>

                    <!-- Tombol Tolak -->
                    <form action="{{ route('pengunjung.status', $p->uid) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="status" value="ditolak">
                      <button type="submit" class="btn btn-sm btn-danger ms-1" title="Tolak">
                        <i class="bi bi-x-circle"></i> Tolak
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-3 d-flex justify-content-center">
          </div>
        </div>
      </div>

</x-layout>
