<x-layout-admin title="Riwayat Tracking">
    <h2 class="mb-4 fw-bold text-color">Riwayat Perubahan Status Kunjungan 🔄</h2>
    <div class="card p-4">
      <!-- Header tabel -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
          <h5 class="fw-bold text-color mb-2 mb-md-0">Data Perubahan Terbaru</h5>
          
          <div class="d-flex gap-2">
              {{-- 1. FILTER FORM (Minggu, Bulan, Tahun, Tipe) --}}
              <form action="{{ route('admin.riwayat') }}" method="GET" class="d-flex gap-2 flex-wrap">
                  
                  {{-- Filter Tipe Pengunjung --}}
                  <select name="tipe" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">
                      <option value="">Semua Tipe</option>
                      <option value="instansi pemerintah" {{ request('tipe') == 'instansi pemerintah' ? 'selected' : '' }}>Instansi Pemerintah</option>
                      <option value="masyarakat umum" {{ request('tipe') == 'masyarakat umum' ? 'selected' : '' }}>Masyarakat Umum</option>
                  </select>    
  
                  {{-- Filter Bulan --}}
                  <select name="bulan" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                      <option value="">Semua Bulan</option>
                      @foreach(range(1,12) as $month)
                          <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                              {{ \Carbon\Carbon::create()->month($month)->locale('id')->monthName }}
                          </option>
                      @endforeach
                  </select>
  
                  {{-- Filter Tahun --}}
                  <select name="tahun" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
                      <option value="">Semua Tahun</option>
                      @for($year = date('Y'); $year >= date('Y') - 2; $year--)
                          <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                              {{ $year }}
                          </option>
                      @endfor
                  </select>
              </form>

              {{-- 2. TOMBOL EXPORT (Memicu Modal) --}}
              <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                  <i class="bi bi-file-earmark-excel"></i> Export
              </button>

              {{-- 3. TOMBOL REFRESH --}}
              <button class="btn btn-sm btn-genz" onclick="window.location.reload()">
                  <i class="bi bi-clock-history"></i>
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
              <td>{{ $trackings->firstItem() + $no }}</td>
              <td>
                <span class="fw-semibold text-color">{{ $t->pengunjung->nama_instansi ?? '-' }}</span>
                @if($t->pengunjung && $t->pengunjung->tipe_pengunjung)
                    <br><small class="text-muted" style="font-size: 0.7rem;">({{ ucfirst($t->pengunjung->tipe_pengunjung) }})</small>
                @endif
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
              <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat perubahan status.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
  
      <!-- Pagination -->
      <div class="mt-3 d-flex justify-content-center">
        {{ $trackings->withQueryString()->links() }}
      </div>
  
    </div> 
    {{-- Tutup Card Utama --}}
  
    {{-- ============================================================== --}}
    {{-- MODAL EXPORT (DIPINDAHKAN KE SINI AGAR TIDAK KEDIP-KEDIP) --}}
    {{-- ============================================================== --}}
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title fw-bold" id="exportModalLabel">Filter Export Excel</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              
              <form action="{{ route('admin.riwayat.export') }}" method="GET">
                  <div class="modal-body">
                      
                      {{-- Filter Tipe Pengunjung (Hidden input agar ngikut filter yang sedang aktif, atau bisa dipilih ulang) --}}
                      <div class="mb-3">
                          <label for="export_tipe" class="form-label small fw-semibold">Tipe Pengunjung</label>
                          <select class="form-select form-select-sm" name="tipe" id="export_tipe">
                              <option value="">Semua Tipe</option>
                              <option value="instansi" {{ request('tipe') == 'instansi' ? 'selected' : '' }}>Instansi Pemerintah</option>
                              <option value="umum" {{ request('tipe') == 'umum' ? 'selected' : '' }}>Masyarakat Umum</option>
                          </select>
                      </div>

                      <div class="mb-3">
                          <label for="export_bulan" class="form-label small fw-semibold">Pilih Bulan</label>
                          <select class="form-select form-select-sm" name="bulan" id="export_bulan">
                              <option value="" selected>Semua Bulan</option>
                              @foreach(range(1, 12) as $month)
                                  <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                                      {{ \Carbon\Carbon::create()->month($month)->locale('id')->monthName }}
                                  </option>
                              @endforeach
                          </select>
                      </div>

                      <div class="mb-3">
                          <label for="export_tahun" class="form-label small fw-semibold">Pilih Tahun</label>
                          <select class="form-select form-select-sm" name="tahun" id="export_tahun">
                              <option value="">Semua Tahun</option>
                              @for($year = date('Y'); $year >= date('Y') - 2; $year--)
                                  <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                              @endfor
                          </select>
                      </div>

                  </div>
                  <div class="modal-footer border-0 pt-0">
                      <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-sm btn-success w-100">
                          <i class="bi bi-download me-1"></i> Download Excel
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</x-layout-admin>