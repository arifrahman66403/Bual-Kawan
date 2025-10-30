<x-layout title="Admin Dashboard">
    <style>
        /* Background & layout */
        .admin-hero{
            background: linear-gradient(135deg,#f8fafc 0%, #eef2ff 50%, #fff7ed 100%);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 20px rgba(16,24,40,0.06);
            overflow: hidden;
        }

        /* floating emoji */
        .hero-emoji{
            position: absolute;
            right: 1rem;
            top: 0.6rem;
            font-size: 2.4rem;
            transform-origin: center;
            animation: float 4s ease-in-out infinite;
            opacity: 0.95;
        }
        @keyframes float{
            0% { transform: translateY(0) rotate(-6deg); }
            50% { transform: translateY(-8px) rotate(6deg); }
            100% { transform: translateY(0) rotate(-6deg); }
        }

        /* cards */
        .card-anim{
            transition: transform .28s ease, box-shadow .28s ease;
            transform: translateY(0);
            will-change: transform;
        }
        .card-anim:hover{
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 24px rgba(15,23,42,0.12);
        }

        /* reveal on scroll */
        .reveal{ opacity:0; transform: translateY(16px); transition: all .6s cubic-bezier(.2,.9,.3,1); }
        .reveal.show{ opacity:1; transform: translateY(0); }

        /* counter style */
        .counter{
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: .4px;
        }

        /* subtle button accent */
        .btn-accent{
            background: linear-gradient(90deg,#4338ca,#06b6d4);
            color: #fff;
            border: 0;
            transition: transform .2s ease, opacity .2s ease;
        }
        .btn-accent:hover{ transform: translateY(-3px); opacity: .95; }
    </style>

    <div class="container-fluid pt-4">
        <div class="admin-hero position-relative reveal">
            <h1 class="h3 mb-2 text-gray-800">🛡️ Admin Dashboard</h1>
            <p class="mb-0 text-muted">Akses Anda: Validasi dan Data Master. Fokus: Pengajuan Pengunjung.</p>
            <div class="hero-emoji">✨</div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4 reveal">
                <div class="card bg-warning text-dark shadow card-anim">
                    <div class="card-body">
                        <div class="h5">
                            <span class="counter" data-target="{{ $pengajuanCount ?? 0 }}">0</span>
                            <span class="ms-2">Pengajuan Baru</span>
                        </div>
                        <a href="{{ route('admin.pengajuan.index') }}" class="text-dark fw-bold">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4 reveal">
                <div class="card shadow card-anim">
                    <div class="card-body">
                        <div class="h6 text-muted">Ringkasan</div>
                        <p class="mb-0">Pantau validasi & data master dengan cepat.</p>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.pengunjung.index') }}" class="btn btn-accent">Data Master Pengunjung</a>
    </div>

    <script>
        // Reveal on scroll
        (function(){
            const reveals = document.querySelectorAll('.reveal');
            const obs = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{
                    if(e.isIntersecting) e.target.classList.add('show');
                });
            }, { threshold: 0.12 });
            reveals.forEach(r=>obs.observe(r));
        })();

        // Count-up animation for counters
        (function(){
            function animateCounter(el, duration = 1200){
                const target = parseInt(el.getAttribute('data-target')) || 0;
                if(target === 0){ el.textContent = '0'; return; }
                const start = 0;
                const startTime = performance.now();
                function tick(now){
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const value = Math.floor(start + (target - start) * easeOutCubic(progress));
                    el.textContent = value.toLocaleString();
                    if(progress < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            }
            function easeOutCubic(t){ return 1 - Math.pow(1 - t, 3); }

            // start when visible
            const counters = document.querySelectorAll('.counter');
            const obs = new IntersectionObserver((entries, observer)=>{
                entries.forEach(entry=>{
                    if(entry.isIntersecting){
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(c => obs.observe(c));
        })();
    </script>
</x-layout>
