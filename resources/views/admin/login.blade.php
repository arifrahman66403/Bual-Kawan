<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .login-card {
      width: 400px;
      background: rgba(33, 37, 41, 0.85);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.45);
      transform: translateY(20px);
      animation: float 0.8s ease-out forwards;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      perspective: 1000px;
    }

    @keyframes float {
      to { transform: translateY(0); }
    }

    .login-card.tilt {
      transform: translateY(0) rotateX(var(--rx)) rotateY(var(--ry));
      box-shadow: 0 20px 40px rgba(0,0,0,0.55);
    }

    .shake {
      animation: shake 0.6s;
    }

    @keyframes shake {
      10%, 90% { transform: translateX(-1px); }
      20%, 80% { transform: translateX(2px); }
      30%, 50%, 70% { transform: translateX(-4px); }
      40%, 60% { transform: translateX(4px); }
    }

    .form-control {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.12);
      color: white;
      transition: all 0.25s ease;
    }

    .form-control:focus {
      background: rgba(255,255,255,0.09);
      border-color: rgba(255,255,255,0.8);
      box-shadow: 0 0 12px rgba(255,255,255,0.06);
      color: white;
    }

    .btn-login {
      background: linear-gradient(90deg,#0d6efd,#6610f2);
      transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .btn-login:active { transform: translateY(1px) scale(0.998); }
    .btn-login:hover { box-shadow: 0 8px 20px rgba(13,110,253,0.28); }

    .bg-gradient-page {
      background: radial-gradient(circle at 10% 20%, rgba(255,255,255,0.03), transparent 10%),
                  radial-gradient(circle at 90% 80%, rgba(255,255,255,0.02), transparent 10%),
                  linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .toggle-pass {
      color: rgba(255,255,255,0.7);
      cursor: pointer;
    }

    .bg-white {
  background-color: white !important; /* !important digunakan untuk memastikan gaya ini menimpa gaya inline */
}

.border-light-gray {
  border: 1px solid #ccc !important;
}


    /* small floating effect for headings */
    .title-float { transform: translateY(-6px); opacity: 0; animation: titleIn 0.7s 0.15s forwards; }
    @keyframes titleIn { to { transform: translateY(0); opacity: 1; } }

    /* toast custom */
    .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1080; }
  </style>
</head>
<body class="bg-gradient-page text-light d-flex align-items-center justify-content-center vh-100">
  <div id="toastArea" class="toast-container"></div>

  <div id="card" class="card p-4 login-card">
    <div class="d-flex align-items-center mb-3">
    <div class="d-flex align-items-center mb-3" style="width:56px;height:56px;background-color: white; border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid #ccc">
        <!-- simple logo -->
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
          <rect x="3" y="3" width="18" height="18" rx="4" fill="url(#g)"/>
          <defs><linearGradient id="g" x1="0" x2="1"><stop offset="0" stop-color="#fff" stop-opacity="0.12"/><stop offset="1" stop-color="#fff" stop-opacity="0.04"/></linearGradient></defs>
        </svg>
      </div>
      <div>
        <h4 class="mb-0">Login Admin</h4>
        <small class="text-light-50">Masuk untuk mengelola konten & pengguna</small>
      </div>
    </div>

    <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label text-light small">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-transparent border-0 text-light">📧</span>
          <input id="email" type="email" name="email" class="form-control" placeholder="nama@domain.com" required autofocus>
          <div class="invalid-feedback text-light">Masukkan email yang valid.</div>
        </div>
      </div>

      <div class="mb-2">
        <label for="password" class="form-label text-light small">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-transparent border-0 text-light">🔒</span>
          <input id="password" type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          <button type="button" class="btn btn-link text-light toggle-pass ps-2 pe-2" id="togglePass" title="Tampilkan/Sembunyikan password" style="text-decoration:none;">👁️</button>
          <div class="invalid-feedback text-light">Password tidak boleh kosong.</div>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="small text-light" style="opacity:0.9; text-decoration:underline;">Lupa password?</a>
      </div>

      @if ($errors->any())
        <div id="serverError" class="alert alert-danger mb-3">{{ $errors->first() }}</div>
      @endif

      <button id="submitBtn" type="submit" class="btn btn-login text-light w-100 d-flex align-items-center justify-content-center">
        <span id="btnText">Masuk</span>
      </button>

      <div class="text-center mt-3">
        <small class="text-light-50">atau kembali ke <a href="/" class="text-white" style="text-decoration:underline;">Beranda</a></small>
      </div>
    </form>

    <div class="text-center mt-3 small" style="opacity:0.78">
      <span>Versi aplikasi 1.0 • © {{ date('Y') }}</span>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      const card = document.getElementById('card');
      const form = document.getElementById('loginForm');
      const pass = document.getElementById('password');
      const toggle = document.getElementById('togglePass');
      const submitBtn = document.getElementById('submitBtn');
      const btnText = document.getElementById('btnText');

      // Tilt effect on mouse move
      card.addEventListener('mousemove', function(e){
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width/2;
        const y = e.clientY - rect.top - rect.height/2;
        const rx = (-y / rect.height) * 6; // rotateX
        const ry = (x / rect.width) * 6;   // rotateY
        card.style.setProperty('--rx', rx + 'deg');
        card.style.setProperty('--ry', ry + 'deg');
        card.classList.add('tilt');
      });
      card.addEventListener('mouseleave', function(){
        card.style.setProperty('--rx', '0deg');
        card.style.setProperty('--ry', '0deg');
        card.classList.remove('tilt');
      });

      // Toggle password visibility
      toggle.addEventListener('click', function(){
        const isHidden = pass.type === 'password';
        pass.type = isHidden ? 'text' : 'password';
        toggle.textContent = isHidden ? '🙈' : '👁️';
      });

      // Client-side validation with shake on invalid
      form.addEventListener('submit', function(e){
        if (!form.checkValidity()) {
          e.preventDefault();
          form.classList.add('was-validated');
          card.classList.add('shake');
          setTimeout(()=> card.classList.remove('shake'), 700);
          const firstInvalid = form.querySelector(':invalid');
          if (firstInvalid) firstInvalid.focus();
          return;
        }
        submitBtn.disabled = true;
        btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Masuk...';
      });

      // If server returned an error, show a Bootstrap toast for better UX
      const serverErrorEl = document.getElementById('serverError');
      if (serverErrorEl) {
        const toastArea = document.getElementById('toastArea');
        const toastId = 'serverToast';
        toastArea.innerHTML = `
          <div id="${toastId}" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body text-white">${serverErrorEl.textContent.trim()}</div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>`;
        const toastEl = document.getElementById(toastId);
        const bsToast = new bootstrap.Toast(toastEl, { delay: 5000 });
        bsToast.show();
        serverErrorEl.style.display = 'none';
      }
    })();
  </script>
</body>
</html>
