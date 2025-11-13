<x-layout-admin title="Dashboard Operator">
    <div class="container-fluid pt-4">
        <h1 class="h3 mb-4 text-gray-800">Scan 📲 Kunjungan</h1>
        
        @if (session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
        @endif

        <div class="card shadow-lg mb-5 scan-card">
            <div class="card-body text-center p-5">
                <div class="qr-icon position-relative d-inline-block mb-3">
                    <i class="bi bi-qr-code-scan display-1 text-primary mb-4"></i>
                    <div class="scan-line"></div>
                </div>

                <h4 class="card-title mb-4">Arahkan Scanner ke Kode QR Pengunjung</h4>
                
                <form id="scanForm" action="{{ route('operator.scan') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-lg mx-auto" style="max-width: 500px;">
                        <input type="text" name="kode_qr" class="form-control input-focus" placeholder="Masukkan Kode QR..." autofocus required>
                        <button id="submitBtn" class="btn btn-primary" type="submit">
                            <i class="bi bi-send-fill me-1"></i> Proses Check-in/out
                        </button>
                    </div>
                    @error('qr_code')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </form>

            </div>
        </div>
        
        <a href="{{ route('operator.riwayat') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-task me-2"></i> Lihat Riwayat Scan Hari Ini
        </a>
    </div>

    <!-- Loading overlay -->
    <div id="loadingOverlay" class="loading-overlay" aria-hidden="true">
        <div class="loading-box">
            <div class="spin" role="status" aria-hidden="true"></div>
            <div>
                <strong>Memproses scan...</strong>
                <div class="text-muted small">Tunggu sebentar</div>
            </div>
        </div>
    </div>

    <style>
        /* Card hover lift */
        .scan-card { transition: transform .25s ease, box-shadow .25s ease; }
        .scan-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,.18); }

        /* QR scan line */
        .qr-icon { width: 1fr; }
        .scan-line {
            position: absolute;
            left: 8%;
            right: 8%;
            height: 6px;
            background: linear-gradient(90deg, rgba(13,110,253,0) 0%, rgba(13,110,253,0.9) 50%, rgba(13,110,253,0) 100%);
            top: -10%;
            border-radius: 3px;
            animation: scan 1.6s linear infinite;
            animation-play-state: paused;
            pointer-events: none;
        }
        @keyframes scan {
            0% { top: -20%; opacity: 0; }
            10% { opacity: 1; }
            50% { top: 50%; opacity: 1; }
            90% { opacity: 1; }
            100% { top: 120%; opacity: 0; }
        }

        /* Input focus glow */
        .input-focus { transition: box-shadow .18s ease; }
        .input-focus:focus { box-shadow: 0 0 0 .25rem rgba(13,110,253,.15); outline: none; }

        /* Loading overlay */
        .loading-overlay{
            position: fixed; inset: 0;
            background: rgba(255,255,255,.75);
            display: none;
            align-items: center; justify-content: center;
            z-index: 1050;
        }
        .loading-box{
            background:#fff;padding:16px;border-radius:8px;
            box-shadow:0 8px 30px rgba(0,0,0,.12);
            display:flex;gap:12px;align-items:center;
        }
        .spin{width:36px;height:36px;border:4px solid #e9ecef;border-top-color:#0d6efd;border-radius:50%;animation:spin 1s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const form = document.getElementById('scanForm');
            const overlay = document.getElementById('loadingOverlay');
            const input = form.querySelector('input[name="kode_qr"]');
            const btn = document.getElementById('submitBtn');
            const scanLine = document.querySelector('.scan-line');

            // Toggle scanning animation while input focused
            input.addEventListener('focus', function(){ if(scanLine) scanLine.style.animationPlayState = 'running'; });
            input.addEventListener('blur', function(){ if(scanLine) scanLine.style.animationPlayState = 'paused'; });

            // On submit show overlay and disable button to give effect
            form.addEventListener('submit', function(e){
                // let the form submit normally; show visual feedback immediately
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
                overlay.style.display = 'flex';
            });

            // Start scan-line for initial autofocus when page loads
            if (document.activeElement === input && scanLine) {
                scanLine.style.animationPlayState = 'running';
            }
        });
    </script>
</x-layout-admin>