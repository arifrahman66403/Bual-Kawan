<x-breadcrumb title="Statistik" />
<x-layout title="Statistik Kunjungan">
  <section aria-labelledby="statTitle">
  <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px;flex-wrap:wrap">
    <h2 id="statTitle" style="margin:0;font-size:1.05rem">Ringkasan Statistik</h2>
    <div style="color:var(--muted);font-weight:700">Data diperbarui: <span id="lastUpdated">—</span></div>
  </div>

  <div class="stats-grid" id="statsGrid">
    <div class="stat-card c-green" aria-hidden="false">
      <div class="stat-icon"><i class="bi bi-people" style="color:#fff;font-size:26px"></i></div>
        <div class="stat-body">
          <h3 id="stat-total">{{ $total_tamu_semua }}</h3>
          <p>Total Pengunjung</p>
        </div>
      </div>

      <div class="stat-card c-blue">
        <div class="stat-icon"><i class="fas fa-book-open" style="color:#fff;font-size:26px"></i></div>
        <div class="stat-body">
          <h3 id="stat-today">{{$total_tamu_hari_ini}}</h3>
          <p>Total Tamu Hari Ini</p>
        </div>
      </div>

      <div class="stat-card c-purple">
        <div class="stat-icon"><i class="bi bi-calendar-month" style="color:#fff;font-size:26px"></i></div>
        <div class="stat-body">
          <h3 id="stat-avg">{{ $total_tamu_bulan_ini }}</h3>
          <p>Total Tamu Bulan Ini</p>
        </div>
      </div>

      <div class="stat-card c-orange">
        <div class="stat-icon"><i class="bi bi-calendar-check" style="color:#fff;font-size:26px"></i></div>
        <div class="stat-body">
          <h3 id="stat-outside">{{ $total_tamu_tahun_ini}}</h3>
          <p>Total Tamu Tahun Ini</p>
        </div>
      </div>
    </div>
  </section>
</x-layout>