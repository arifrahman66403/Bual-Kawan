<x-layout>
    <style>
        /* Page background and hero */
        .superadmin-hero{
            background: linear-gradient(135deg, #f8fafc 0%, #e9f2ff 50%, #f0f7ff 100%);
            background-size: 200% 200%;
            animation: bgShift 10s ease infinite;
            padding: 2rem;
            border-radius: .5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 20px rgba(20,60,120,0.06);
        }
        @keyframes bgShift{
            0%{background-position:0% 50%}
            50%{background-position:100% 50%}
            100%{background-position:0% 50%}
        }

        /* Cards */
        .stat-card{
            overflow: hidden;
            border: none;
            border-radius: .75rem;
            position: relative;
            transition: transform .35s cubic-bezier(.22,.9,.26,1), box-shadow .35s;
            transform: translateY(6px);
            box-shadow: 0 6px 18px rgba(4,18,50,0.04);
            animation: fadeInUp .6s ease both;
        }
        .stat-card:hover{
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 18px 40px rgba(10,35,80,0.12);
        }
        @keyframes fadeInUp{
            from { opacity: 0; transform: translateY(16px) }
            to   { opacity: 1; transform: translateY(0) }
        }

        .stat-body{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            padding:1.25rem;
        }
        .stat-info{
            font-weight:600;
            font-size:1rem;
            color: rgba(255,255,255,0.95);
        }
        .stat-num{
            font-size:1.6rem;
            font-weight:700;
            letter-spacing:.6px;
            color: rgba(255,255,255,0.98);
        }

        /* subtle animated underline on button */
        .glow-btn{
            position:relative;
            overflow:hidden;
        }
        .glow-btn::after{
            content:'';
            position:absolute;
            left:-50%;
            top:0;
            width:50%;
            height:100%;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
            transform: skewX(-20deg);
            transition: all .6s ease;
        }
        .glow-btn:hover::after{
            left:150%;
        }

        /* small floating emblem */
        .floating-emoji{
            display:inline-block;
            transform-origin:center;
            animation: floaty 3s ease-in-out infinite;
            margin-right:.5rem;
        }
        @keyframes floaty{
            0%{ transform: translateY(0) rotate(0deg) }
            50%{ transform: translateY(-6px) rotate(6deg) }
            100%{ transform: translateY(0) rotate(0deg) }
        }

        /* make small counters visually separated on small screens */
        .stats-row{ gap:1rem; }
    </style>

    <div class="container-fluid pt-4">
        <div class="superadmin-hero">
            <h1 class="h3 mb-2 text-gray-800">
                <span class="floating-emoji">👋</span>
                Superadmin Dashboard
            </h1>
            <p class="mb-3 text-muted">Akses Anda: Penuh. Fokus: Manajemen Akun dan Sistem.</p>

            <div class="row stats-row">
                <div class="col-md-4 mb-4">
                    <div class="card stat-card bg-primary text-white shadow">
                        <div class="card-body stat-body">
                            <div>
                                <div class="stat-info">Total Akun Admin</div>
                                <div class="stat-num">
                                    <span class="count" data-target="{{ $totalAdmins ?? 0 }}">0</span>
                                </div>
                            </div>
                            <div>
                                <!-- small decorative svg -->
                                <svg width="54" height="54" viewBox="0 0 24 24" fill="none" aria-hidden>
                                    <circle cx="12" cy="7" r="3" fill="rgba(255,255,255,0.18)"/>
                                    <path d="M4.5 20a6.5 6.5 0 0115 0" stroke="rgba(255,255,255,0.12)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div style="height:4px; background: linear-gradient(90deg, rgba(255,255,255,0.2), rgba(255,255,255,0.08));"></div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card stat-card bg-success text-white shadow">
                        <div class="card-body stat-body">
                            <div>
                                <div class="stat-info">Total Akun Operator</div>
                                <div class="stat-num">
                                    <span class="count" data-target="{{ $totalOperators ?? 0 }}">0</span>
                                </div>
                            </div>
                            <div>
                                <svg width="54" height="54" viewBox="0 0 24 24" fill="none" aria-hidden>
                                    <rect x="4" y="4" width="16" height="12" rx="2" fill="rgba(255,255,255,0.14)"/>
                                    <path d="M8 20h8" stroke="rgba(255,255,255,0.12)" stroke-width="1.6" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                        <div style="height:4px; background: linear-gradient(90deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06));"></div>
                    </div>
                </div>
            </div>

            <a href="{{ route('superadmin.users.index') }}" class="btn btn-primary glow-btn">Kelola User</a>
        </div>
    </div>

    <script>
        // count-up animation for the numeric stats
        document.addEventListener('DOMContentLoaded', function () {
            function animateCount(el, duration = 1200) {
                const target = parseInt(el.getAttribute('data-target')) || 0;
                if (target === 0) { el.textContent = '0'; return; }
                const start = 0;
                const startTime = performance.now();
                function step(now) {
                    const progress = Math.min((now - startTime) / duration, 1);
                    // easeOutCubic
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(start + (target - start) * eased);
                    el.textContent = current.toLocaleString();
                    if (progress < 1) requestAnimationFrame(step);
                    else el.textContent = target.toLocaleString();
                }
                requestAnimationFrame(step);
            }

            document.querySelectorAll('.count').forEach(function (el, idx) {
                // small stagger for nicer effect
                setTimeout(function () { animateCount(el, 1100 + idx * 200); }, idx * 180);
            });
        });
    </script>
</x-layout>