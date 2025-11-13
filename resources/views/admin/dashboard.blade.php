<x-layout-admin title="Admin Dashboard">
     <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
        <h2 class="mb-4 fw-bold text-color">Statistik Kunjungan $Flash$ ✨</h2>
        
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-1">
                    <i class="bi bi-calendar-check fs-4 mb-2"></i>
                    <div class="big-number">{{ $total_tamu_hari_ini }}</div>
                    <div class="text-muted-genz">Total Tamu Hari Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-2">
                    <i class="bi bi-graph-up fs-4 mb-2"></i>
                    <div class="big-number">+12%</div>
                    <div class="text-muted-genz">Kenaikan Kunjungan</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-3">
                    <i class="bi bi-hourglass-split fs-4 mb-2"></i>
                    <div class="big-number">15 Min</div>
                    <div class="text-muted-genz">Rata-rata Durasi</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-4">
                    <i class="bi bi-people fs-4 mb-2"></i>
                    <div class="big-number">{{ $total_tamu_semua }}</div>
                    <div class="text-muted-genz">Total Tamu Sepanjang Masa</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold text-color">Tren Kunjungan 7 Hari Terakhir</h5>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold text-color">Distribusi Tujuan</h5>
                    <canvas id="donutChart"></canvas>
                    <div class="mt-3 text-center text-muted-genz">Data berdasarkan 500 kunjungan terakhir.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Counter animation
        (function animateCounters() {
            const counters = document.querySelectorAll('.count');
            counters.forEach(el => {
                const target = parseFloat(el.dataset.target || 0);
                const duration = 1200;
                const start = performance.now();
                const prefix = el.dataset.prefix || '';
                const suffix = el.dataset.suffix || '';
                const format = el.dataset.format || '';

                function formatValue(val){
                    if(format === 'compact'){
                        if(val >= 1e6) return Math.round(val/1e6*10)/10 + 'M';
                        if(val >= 1e3) return Math.round(val/1e3*10)/10 + 'K';
                    }
                    return Math.round(val).toString();
                }

                function step(now){
                    const progress = Math.min((now - start) / duration, 1);
                    const value = Math.round(progress * target);
                    el.textContent = prefix + formatValue(value) + suffix;
                    if(progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        })();

        // Simple theme colors (override as needed)
        const colors = {
            primary: getComputedStyle(document.documentElement).getPropertyValue('--bs-primary') || '#0d6efd',
            one: '#6f42c1',
            two: '#0d6efd',
            three: '#198754',
            four: '#ffc107'
        };

        // Line chart (7 days)
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const labels = (function last7Days(){
            const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
            // rotate so last day is today (simple approach)
            const today = new Date().getDay(); // 0-6 Sun-Sat
            const arr = [];
            for(let i=6;i>=0;i--){
                const d = new Date();
                d.setDate(d.getDate() - i);
                arr.push(days[d.getDay()] + ' ' + d.getDate());
            }
            return arr;
        })();

        const lineData = {
            labels,
            datasets: [{
                label: 'Kunjungan',
                data: [60, 72, 55, 80, 90, 75, 45], // replace with dynamic data
                borderColor: colors.two,
                backgroundColor: (ctx) => {
                    const gradient = ctx.createLinearGradient(0,0,0,200);
                    gradient.addColorStop(0, 'rgba(13,110,253,0.18)');
                    gradient.addColorStop(1, 'rgba(13,110,253,0.02)');
                    return gradient;
                },
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
            }]
        };

        new Chart(lineCtx, {
            type: 'line',
            data: lineData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        ticks: { precision: 0 },
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 900,
                    easing: 'easeOutCubic'
                }
            }
        });

        // Donut chart
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        const donut = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lobby','Ruang Meeting','Kantin','Lainnya'],
                datasets: [{
                    data: [240, 120, 80, 60], // replace with dynamic
                    backgroundColor: [colors.two, colors.one, colors.three, colors.four],
                    hoverOffset: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom' }
                },
                animation: {
                    animateRotate: true,
                    duration: 900,
                    easing: 'easeOutBack'
                }
            }
        });

        // Light entrance for charts after they render
        document.querySelectorAll('canvas').forEach((c, i) => {
            c.style.opacity = 0;
            setTimeout(() => { c.style.transition = 'opacity 420ms ease'; c.style.opacity = 1; }, 100 + i * 120);
        });
    </script>
</x-layout>
