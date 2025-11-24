<x-layout-admin title="Verifikasi Pengunjung">
  <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
    <h2 class="mb-4 fw-bold text-color">Daftar Pengajuan Kunjungan 📋</h2>
      @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {!! session('success') !!}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {!! session('error') !!}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

        <!-- Filter -->
        <form method="GET" action="{{ route('admin.pengajuan') }}" class="mb-4">
            <div class="card p-3 filter-box shadow-sm">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel-fill me-2"></i> Filter dan Pencarian</h5>
                
                <div class="row g-3">
                    
                    {{-- 1. FILTER STATUS --}}
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label fw-semibold">Status Kunjungan</label>
                        <select name="status" id="statusFilter" class="form-select form-select-sm">
                            <option value="all">-- Semua Status --</option>
                            <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Menunggu (Pengajuan)</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="kunjungan" {{ request('status') == 'kunjungan' ? 'selected' : '' }}>Kunjungan Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- 2. FILTER TIPE PENGUNJUNG --}}
                    <div class="col-md-3">
                        <label for="tipeFilter" class="form-label fw-semibold">Tipe Pengunjung</label>
                        <select name="tipe" id="tipeFilter" class="form-select form-select-sm">
                            <option value="all">-- Semua Tipe --</option>
                            {{-- 💡 Sesuaikan nilai value dengan kolom 'tipe_pengunjung' di DB --}}
                            <option value="instansi pemerintah" {{ request('tipe') == 'instansi pemerintah' ? 'selected' : '' }}>Instansi Pemerintah</option>
                            <option value="masyarakat umum" {{ request('tipe') == 'masyarakat umum' ? 'selected' : '' }}>Umum/Perorangan</option>
                        </select>
                    </div>
                    
                    {{-- 3. KOLOM PENCARIAN (SEARCH) --}}
                    <div class="col-md-4">
                        <label for="searchQuery" class="form-label fw-semibold">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" id="searchQuery" class="form-control" placeholder="Cari Instansi / Perwakilan..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>

                    {{-- 4. TOMBOL RESET FILTER --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.pengajuan') }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tabel -->
        <div class="card p-4">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h5 class="fw-bold text-color mb-2 mb-md-0">Data Pengajuan Terbaru</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pengajuan.export') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-download"></i> Export Excel
                </a>
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
            @forelse($pengunjungs as $no => $p)
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

              <!-- Tombol Aksi (tidak memakai data-bs-toggle / data-bs-target) -->
              <td class="text-nowrap">
          <!-- Tombol Detail (boleh tetap) -->
          <a href="{{ route('admin.pengajuan.show', $p->uid) }}" class="btn btn-sm btn-genz" title="Lihat Detail"><i class="bi bi-eye"></i></a>

            @if($p->status == 'kunjungan')
            <!-- Jika sudah disetujui: tampilkan tombol Selesai -->
            <button type="button"
              class="btn btn-sm btn-primary ms-1 btn-action"
              data-status="selesai"
              data-id="{{ $p->uid }}"
              data-icon="bi-check2-square text-primary"
              data-message="Apakah kamu yakin ingin menandai pengajuan ini SELESAI?">
              <i class="bi bi-check2-square"></i> Selesai
            </button>
            @elseif($p->status == 'disetujui' || $p->status == 'selesai')
            <button class="btn btn-sm btn-secondary ms-1" title="Tidak Ada Aksi Lagi" disabled><i class="bi bi-lock"></i></button>
            @else
            <!-- Tombol Setujui: hanya data-* attributes, buka modal lewat JS -->
            <button type="button"
              class="btn btn-sm btn-success ms-1 btn-action"
              data-status="disetujui"
              data-id="{{ $p->uid }}"
              data-icon="bi-check-circle text-success"
              data-message="Apakah kamu yakin ingin MENYETUJUI pengajuan ini?">
              <i class="bi bi-check2-circle"></i> Terima
            </button>

            <!-- Tombol Tolak
            <button type="button"
              class="btn btn-sm btn-danger ms-1 btn-action"
              data-status="ditolak"
              data-id="{{ $p->uid }}"
              data-icon="bi-x-circle text-danger"
              data-message="Apakah kamu yakin ingin MENOLAK pengajuan ini?">
              <i class="bi bi-x-circle"></i> Tolak
            </button -->
            @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Tidak ada data pengajuan kunjungan.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3 d-flex justify-content-center">
        {{ $pengunjungs->links() }}
      </div>
    </div>
  </div>

  <!-- ======================================================
       Single Modal (LETakkan hanya SEKALI di luar loop)
       ====================================================== -->
  <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="actionModalLabel">Konfirmasi Aksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body text-center">
          <div class="action-icon mb-3">
            <i id="modalIcon" class="bi fs-1"></i>
          </div>
          <p id="modalMessage" class="fs-5 fw-semibold"></p>
          <p class="text-muted">ID Pengajuan: <span id="modalIdDisplay" class="fw-bold"></span></p>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

          <!-- Form yang akan dikirim ketika user klik Lanjutkan -->
          <form id="modalActionForm" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="status" id="modalStatusValue">
            <button type="submit" class="btn" id="modalConfirmButton">Lanjutkan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================================================
       Script: isi modal + buka modal hanya lewat JS (NO data-bs attributes)
       ====================================================== -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('actionModal');
    if (!modalEl) return;

    const bsModal = new bootstrap.Modal(modalEl, {});
    const modalIcon = document.getElementById('modalIcon');
    const modalMessage = document.getElementById('modalMessage');
    const modalIdDisplay = document.getElementById('modalIdDisplay');
    const modalConfirmButton = document.getElementById('modalConfirmButton');
    const modalStatusValue = document.getElementById('modalStatusValue');
    const modalForm = document.getElementById('modalActionForm');

    document.querySelectorAll('.btn-action').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const status = this.dataset.status;
        const icon = this.dataset.icon;
        const message = this.dataset.message;

        modalIcon.className = `bi fs-1 ${icon}`;
        modalMessage.textContent = message;
        modalIdDisplay.textContent = id;

        modalConfirmButton.className = `btn ${status === 'disetujui' ? 'btn-success' : 'btn-danger'}`;
        modalStatusValue.value = status;

        // gunakan helper url() agar sesuai dengan base URL Laravel (route admin)
        modalForm.action = `{{ url('pengajuan') }}/${id}/status`;

        bsModal.show();
      });
    });

    modalForm.addEventListener('submit', function() {
      modalConfirmButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    });
  });
</script>

</x-layout-admin>
