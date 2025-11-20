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

    /* Default form control (kept for dark-on-dark if needed) */
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

    /* Page background changed to white and bring text to dark for light mode */
    .bg-gradient-page {
      background: #ffffff;
    }

    /* Make body text dark when using the white page background */
    body.bg-gradient-page {
      color: #212529;
    }
    /* Ensure common utility classes inside the white page inherit dark color */
    body.bg-gradient-page .text-white,
    body.bg-gradient-page .text-light {
      color: #212529 !important;
    }

    /* Adjust form controls for the white page background */
    body.bg-gradient-page .form-control {
      background: rgba(0,0,0,0.04);
      border: 1px solid rgba(0,0,0,0.12);
      color: #212529;
    }
    body.bg-gradient-page .form-control:focus {
      background: rgba(0,0,0,0.06);
      border-color: rgba(33,37,41,0.15);
      box-shadow: 0 0 8px rgba(0,0,0,0.04);
      color: #212529;
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
  </style>
</head>
<body class="bg-gradient-page text-light d-flex align-items-center justify-content-center vh-100">
  <div id="toastArea" class="toast-container"></div>

  <div id="card" class="card p-5 login-card">
    <!-- Header with Logo -->
    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary">
      <div style="width:56px;height:56px;background-color: white; border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid #ccc;flex-shrink:0;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="3" width="18" height="18" rx="4" fill="#0d6efd"/>
          <text x="12" y="17" font-size="12" font-weight="bold" fill="white" text-anchor="middle">BK</text>
        </svg>
      </div>
      <div class="ms-3">
        <h5 class="mb-0 fw-bold text-white">Bual Kawan</h5>
        <small class="text-light opacity-75">Admin Login</small>
      </div>
    </div>

    <!-- Title -->
    <h3 class="text-white fw-bold mb-1">Selamat Datang</h3>
    <p class="text-light opacity-75 mb-4">Masukkan kredensial Anda untuk melanjutkan</p>

    <!-- Form -->
    <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <!-- Email Field -->
      <div class="mb-4">
        <label for="email" class="form-label text-white fw-5 mb-2">Email Address</label>
        <div class="input-group">
          <span class="input-group-text bg-transparent border-light border-opacity-25 text-light">📧</span>
          <input id="email" type="email" name="email" class="form-control border-light border-opacity-25" placeholder="admin@example.com" required autofocus>
        </div>
        <div class="invalid-feedback text-warning d-block mt-1">Masukkan email yang valid.</div>
      </div>

      <!-- Password Field -->
      <div class="mb-3">
        <label for="password" class="form-label text-white fw-5 mb-2">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-transparent border-light border-opacity-25 text-light">🔒</span>
          <input id="password" type="password" name="password" class="form-control border-light border-opacity-25" placeholder="Masukkan password" required>
          <button type="button" class="btn btn-outline-secondary toggle-pass border-light border-opacity-25" id="togglePass" title="Toggle password">
            <span id="toggleIcon">👁️</span>
          </button>
        </div>
        <div class="invalid-feedback text-warning d-block mt-1">Password tidak boleh kosong.</div>
      </div>

      <!-- Forgot Password Link -->
      <div class="mb-4 text-end">
        <a href="#" class="small text-light opacity-75 text-decoration-none">Lupa password?</a>
      </div>

      <!-- Error Message -->
      @if ($errors->any())
        <div id="serverError" class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
          <strong>Error!</strong> {{ $errors->first() }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <!-- Submit Button -->
      <button id="submitBtn" type="submit" class="btn btn-login text-white w-100 fw-bold py-2 mb-3">
        <span id="btnText">Masuk</span>
      </button>

      <!-- Footer -->
      <div class="text-center small">
        <p class="text-light opacity-75 mb-2">Kembali ke <a href="/" class="text-white fw-bold text-decoration-none">Beranda</a></p>
        <span class="text-light opacity-50 d-block">Versi 1.0 • © {{ date('Y') }} Bual Kawan</span>
      </div>
    </form>
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
        toggle.textContent = isHidden ? '🙈' : '🙉';
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
