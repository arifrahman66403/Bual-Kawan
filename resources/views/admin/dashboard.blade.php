<style>
    /* CSS tambahan untuk memastikan Canvas memiliki batas tinggi yang jelas */
    .chart-fixed-height {
        /* Tinggi standar untuk desktop (sekitar 350-400px di bawah header) */
        height: 380px; 
        width: 100%;
        /* Penting: Pastikan pembungkus ini bukan Flex atau Grid kecuali height diset fix */
    }

    /* Override untuk tampilan yang lebih compact di mobile */
    @media (max-width: 768px) {
        .chart-fixed-height {
            height: 300px;
        }
    }
</style>
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
                <i class="bi bi-calendar-month fs-4 mb-2"></i>
                <div class="big-number">{{ $total_tamu_bulan_ini }}</div>
                <div class="text-muted-genz">Total Tamu Bulan Ini</div>
            </div>
            </div>
            <div class="col-lg-3 col-6">
            <div class="card p-3 stat-card-bg stat-card-3">
                <i class="bi bi-calendar-check fs-4 mb-2"></i>
                <div class="big-number">{{ $total_tamu_tahun_ini }}</div>
                <div class="text-muted-genz">Total Tamu Tahun Ini</div>
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
                {{-- Hapus h-100 dari card, kita akan menggunakan tinggi fixed pada wrapper canvas --}}
                <div class="card p-4"> 
                    <h5 class="fw-bold text-color">Tren Kunjungan 7 Hari Terakhir</h5>
                    
                    {{-- WRAPPER BARU DENGAN TINGGI TETAP --}}
                    <div class="chart-fixed-height">
                        <canvas id="lineChart"></canvas>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4">
                {{-- Hapus h-100 dari card --}}
                <div class="card p-4">
                    <h5 class="fw-bold text-color">Distribusi Tujuan</h5>
                    
                    {{-- WRAPPER BARU DENGAN TINGGI TETAP --}}
                    <div class="chart-fixed-height">
                        <canvas id="donutChart"></canvas>
                    </div>
                    
                    <div class="mt-3 text-center text-muted-genz">Data berdasarkan {{ $total_tamu_semua }} kunjungan terakhir.</div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // --- Data dari Controller (PHP ke JS) ---
        const trendLabels = @json($chartLabels);
        const trendDataValues = @json($chartData);
        const tipeLabels = @json($tipeLabels);
        const tipeDataValues = @json($tipeData);

        // Simple theme colors
        const colors = {
            primary: getComputedStyle(document.documentElement).getPropertyValue('--bs-primary') || '#0d6efd',
            one: '#6f42c1',
            two: '#0d6efd',
            three: '#198754',
            four: '#ffc107',
            five: '#dc3545',
            six: '#0dcaf0'
        };

        // --- JALANKAN SEMUA KODE SETELAH DOM SIAP ---
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Counter Animation (Tidak diubah) ---
            (function animateCounters() {
                const counters = document.querySelectorAll('.big-number'); 
                counters.forEach(el => {
                    const targetText = el.textContent.replace(/[^0-9]/g, '');
                    const target = parseInt(targetText) || 0;
                    if (target === 0) return;

                    const duration = 1200;
                    const start = performance.now();
                    
                    function step(now){
                        const progress = Math.min((now - start) / duration, 1);
                        const value = Math.round(progress * target);
                        el.textContent = value.toString();
                        if(progress < 1) requestAnimationFrame(step);
                    }
                    requestAnimationFrame(step);
                });
            })();

            // --- Line Chart (Trend Kunjungan) ---
            const lineCanvas = document.getElementById('lineChart');
            if (!lineCanvas) return console.error("Canvas ID 'lineChart' not found.");
            const lineCtx = lineCanvas.getContext('2d');
            
            // --- PERBAIKAN: Hitung Max Data Dinamis untuk Line Chart ---
            const maxData = Math.max(0, ...trendDataValues); // Pastikan min 0 jika array kosong
            // Tetapkan suggestedMax 10% lebih tinggi dari nilai maksimal data, minimal 10
            const dynamicMax = Math.max(10, maxData + Math.ceil(maxData * 0.1)); 
            
            const lineData = {
                labels: trendLabels,
                datasets: [{
                    label: 'Total Tamu',
                    data: trendDataValues,
                    borderColor: colors.two,
                    backgroundColor: (context) => {
                        // Perbaikan gradient yang aman
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 200); 
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
                    scales: {
                        x: { grid: { display: false } },
                        y: { 
                            ticks: { 
                                stepSize: 1,
                                precision: 0
                            },
                            beginAtZero: true,
                            // --- Gunakan Max Dinamis untuk kontrol ketinggian ---
                            suggestedMax: dynamicMax 
                        }
                    },
                    animation: { duration: 900, easing: 'easeOutCubic' }
                }
            });

            // --- Donut Chart (Distribusi Tipe Pengunjung) ---
            const donutCanvas = document.getElementById('donutChart');
            if (!donutCanvas) return console.error("Canvas ID 'donutChart' not found.");
            const donutCtx = donutCanvas.getContext('2d');
            
            const donutDataFinal = (tipeDataValues && tipeDataValues.length > 0) ? tipeDataValues : [1]; 
            const donutLabelsFinal = (tipeLabels && tipeLabels.length > 0) ? tipeLabels : ['Belum ada data'];
            const donutColors = [colors.two, colors.one, colors.three, colors.four, colors.five, colors.six];

            const donut = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: donutLabelsFinal,
                    datasets: [{
                        data: donutDataFinal,
                        backgroundColor: donutColors.slice(0, donutDataFinal.length),
                        hoverOffset: 8,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        // Biarkan ini menggunakan default behavior yang berfungsi
                        legend: { 
                            position: 'bottom', 
                            labels: { usePointStyle: true } 
                        } 
                    },
                    animation: { animateRotate: true, duration: 900, easing: 'easeOutBack' }
                }
            });

            // Light entrance for charts
            document.querySelectorAll('canvas').forEach((c, i) => {
                c.style.opacity = 0;
                setTimeout(() => { c.style.transition = 'opacity 420ms ease'; c.style.opacity = 1; }, 100 + i * 120);
            });
            
        }); // Penutup DOMContentLoaded
    </script>
</x-layout-admin>
