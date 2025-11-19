<x-breadcrumb title="Statistik" />
<x-layout title="Statistik Kunjungan">
  <section aria-labelledby="statTitle">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px;flex-wrap:wrap">
          <h2 id="statTitle" style="margin:0;font-size:1.05rem">Ringkasan Statistik</h2>
          <div style="color:var(--muted);font-weight:700">Data diperbarui: <span id="lastUpdated">—</span></div>
        </div>

        <div class="stats-grid" id="statsGrid">
          <div class="stat-card c-green" aria-hidden="false">
            <div class="stat-icon"><i class="fas fa-users" style="color:#fff;font-size:26px"></i></div>
            <div class="stat-body">
              <h3 id="stat-total">2.345</h3>
              <p>Total Pengunjung</p>
            </div>
          </div>

          <div class="stat-card c-blue">
            <div class="stat-icon"><i class="fas fa-book-open" style="color:#fff;font-size:26px"></i></div>
            <div class="stat-body">
              <h3 id="stat-today">28</h3>
              <p>Buku Tamu Hari Ini</p>
            </div>
          </div>

          <div class="stat-card c-purple">
            <div class="stat-icon"><i class="fas fa-chart-line" style="color:#fff;font-size:26px"></i></div>
            <div class="stat-body">
              <h3 id="stat-avg">4.2</h3>
              <p>Rata-rata Kunjungan / Hari</p>
            </div>
          </div>

          <div class="stat-card c-orange">
            <div class="stat-icon"><i class="fas fa-globe-asia" style="color:#fff;font-size:26px"></i></div>
            <div class="stat-body">
              <h3 id="stat-outside">512</h3>
              <p>Tamu Luar Daerah</p>
            </div>
          </div>

          <div class="stat-card c-pink">
            <div class="stat-icon"><i class="fas fa-thumbs-up" style="color:#fff;font-size:26px"></i></div>
            <div class="stat-body">
              <h3 id="stat-feedback">89%</h3>
              <p>Feedback Positif</p>
            </div>
          </div>
        </div>
      </section>

      <section aria-labelledby="chartsTitle">
        <h3 id="chartsTitle" style="margin:8px 0 12px">Grafik Kunjungan</h3>
        <div class="charts-grid">
          <div class="chart-card">
            <h4>Pengunjung per Bulan</h4>
            <div class="canvas-wrap"><canvas id="chartMonthly"></canvas></div>
          </div>
          <div class="chart-card">
            <h4>Asal Daerah (Top 5)</h4>
            <div class="canvas-wrap"><canvas id="chartOrigin"></canvas></div>
          </div>
          <div class="chart-card">
            <h4>Rating Feedback</h4>
            <div class="canvas-wrap"><canvas id="chartFeedback"></canvas></div>
          </div>
      </div>
  </section>
</x-layout>