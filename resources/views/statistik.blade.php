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
<script>
/* @ziedanet
   Script: Statistik page (Chart.js + mobile menu interactions)
   Ringkas & hanya untuk fitur yang dipakai
*/
(function(){
  // helper: format date
  function formatDate(d){ return d.toLocaleString('id-ID', { day:'2-digit', month:'short', year:'numeric' }); }
  document.getElementById('lastUpdated').textContent = formatDate(new Date());

  /* ===== dummy data for charts (Chart.js) =====
     Data dummy ini tidak diubah (sesuai permintaan) @ziedanet */
  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const monthlyData = [120,180,150,210,250,290,310,330,300,280,260,300];
  const origins = ['Siak','Pekanbaru','Riau','Bandung','Jakarta'];
  const originData = [420,180,120,90,60];
  const ratingLabels = ['5 ★','4 ★','3 ★','2 ★','1 ★'];
  const ratingData = [420,150,50,20,10];

  /* ===== Chart: Monthly (bar) - fix: animation not looping and stable sizing
     Jangan ubah bagian ini karena sudah sesuai (perbaikan sebelumnya) @ziedanet */
  const ctxM = document.getElementById('chartMonthly').getContext('2d');
  const chartMonthly = new Chart(ctxM, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Pengunjung',
        data: monthlyData.slice(), // use copy
        backgroundColor: months.map((m,i)=>`rgba(${50 + i*8},${120 + (i*6)},${200 - i*5},0.9)`),
        borderRadius:6,
        barThickness:18
      }]
    },
    options: {
      plugins:{legend:{display:false}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{
        duration:600,
        easing:'easeOutCubic',
        // don't animate on resize repeatedly
        animateRotate:false,
        animateScale:false
      },
      scales:{
        x:{grid:{display:false}, ticks:{maxRotation:0,minRotation:0}},
        y:{beginAtZero:true}
      },
      interaction:{mode:'index', intersect:false}
    }
  });

  /* ===== Chart: Origin (doughnut) ===== */
  const ctxO = document.getElementById('chartOrigin').getContext('2d');
  const chartOrigin = new Chart(ctxO, {
    type: 'doughnut',
    data: {
      labels: origins,
      datasets: [{
        data: originData.slice(),
        backgroundColor: ['#2ecc71','#3498db','#9b59b6','#f39c12','#ff6b9f'],
        hoverOffset:8
      }]
    },
    options:{
      plugins:{legend:{position:'bottom'}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{duration:600,easing:'easeOutCubic'}
    }
  });

  /* ===== Chart: Feedback (pie) ===== */
  const ctxF = document.getElementById('chartFeedback').getContext('2d');
  const chartFeedback = new Chart(ctxF, {
    type: 'pie',
    data: {
      labels: ratingLabels,
      datasets: [{
        data: ratingData.slice(),
        backgroundColor: ['#27ae60','#2f80ed','#f2c94c','#f2994a','#eb5757'],
      }]
    },
    options:{
      plugins:{legend:{position:'bottom'}},
      responsive:true,
      maintainAspectRatio:false,
      animation:{duration:600,easing:'easeOutCubic'}
    }
  });

  /* ===== Mobile bottom nav interactions & submenu slide-up =====
     Komentar: interaksi mobile menu (tidak mengubah struktur) @ziedanet */
  const mbTentang = document.getElementById('mb-tentang');
  const mobileSubmenu = document.getElementById('mobileSubmenu');

  function closeMobileSub(){ mobileSubmenu.style.display='none'; mobileSubmenu.setAttribute('aria-hidden','true'); }
  function openMobileSub(){ mobileSubmenu.style.display='block'; mobileSubmenu.setAttribute('aria-hidden','false'); mobileSubmenu.style.transform='translateY(0)'; mobileSubmenu.style.opacity='1'; }

  mbTentang.addEventListener('click', function(e){
    e.stopPropagation();
    // toggle submenu
    if (mobileSubmenu.style.display === 'block') closeMobileSub();
    else openMobileSub();
  });

  // click outside to close mobile submenu
  document.addEventListener('click', function(e){
    const isClickOnMenu = e.target.closest('.menu') || e.target.closest('#mobileSubmenu') || e.target.closest('#mb-tentang');
    if (!isClickOnMenu) closeMobileSub();
  });

  // mobile nav button navigation
  document.getElementById('mb-home').addEventListener('click', ()=> location.href = document.getElementById('mb-home').dataset.href );
  document.getElementById('mb-bukutamu').addEventListener('click', ()=> location.href = document.getElementById('mb-bukutamu').dataset.href );
  document.getElementById('mb-stat').addEventListener('click', ()=> location.href = document.getElementById('mb-stat').dataset.href );

  /* ===== contact form demo handler (keperluan tampilan saja) @ziedanet */
  window.onContactSend = function(e){
    e.preventDefault();
    const name = document.getElementById('contactName').value.trim();
    const email = document.getElementById('contactEmail').value.trim();
    const msg = document.getElementById('contactMessage').value.trim();
    if(!name||!email||!msg){ alert('Lengkapi form kontak.'); return; }
    alert('Terima kasih, pesan Anda sudah dikirim (demo).');
    document.getElementById('contactForm').reset();
  };

  /* Accessibility: hide mobile submenu when resizing to desktop @ziedanet */
  window.addEventListener('resize', function(){
    if(window.innerWidth > 720) closeMobileSub();
  });

  /* END @ziedanet */
})();
</script>
</x-layout>