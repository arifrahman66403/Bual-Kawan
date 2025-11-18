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

              <!-- Tombol Aksi (tidak memakai data-bs-toggle / data-bs-target) -->
              <td class="text-nowrap">
                <!-- Tombol Detail (boleh tetap) -->
                <button class="btn btn-sm btn-genz" title="Lihat Detail"><i class="bi bi-eye"></i></button>

                <!-- Tombol Setujui: hanya data-* attributes, buka modal lewat JS -->
                <button type="button"
                        class="btn btn-sm btn-success ms-1 btn-action"
                        data-status="disetujui"
                        data-id="{{ $p->uid }}"
                        data-icon="bi-check-circle text-success"
                        data-message="Apakah kamu yakin ingin MENYETUJUI pengajuan ini?">
                  <i class="bi bi-check2-circle"></i> Terima
                </button>

                <!-- Tombol Tolak -->
                <button type="button"
                        class="btn btn-sm btn-danger ms-1 btn-action"
                        data-status="ditolak"
                        data-id="{{ $p->uid }}"
                        data-icon="bi-x-circle text-danger"
                        data-message="Apakah kamu yakin ingin MENOLAK pengajuan ini?">
                  <i class="bi bi-x-circle"></i> Tolak
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-3 d-flex justify-content-center">
        <!-- Links -->
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

        // gunakan helper url() agar sesuai dengan base URL Laravel
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
