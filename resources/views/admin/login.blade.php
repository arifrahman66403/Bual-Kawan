<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sederhana</title>
    <link rel="icon" type="image/png" href="https://bualkawan.siakkab.go.id/logo-bualkawan2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Gaya Sederhana dan Minimalis */
        body {
            background-color: #f8f9fa; /* Latar belakang sangat terang */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px; /* Ukuran lebih kecil */
            padding: 20px;
        }

        .login-card {
            background: white;
            border: 1px solid #dee2e6; /* Border tipis */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Shadow lembut */
            padding: 30px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 5px;
        }

        .login-header p {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .form-control, .input-group-text, .btn-primary {
            border-radius: 6px;
        }

        .input-group-text {
            background-color: #e9ecef;
            border-right: none;
        }
        
        .form-control:focus + .input-group-text {
            border-color: #86b7fe; /* Warna fokus Bootstrap default */
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }
        
        .btn-login {
            width: 100%;
            padding: 10px 15px;
            font-weight: 600;
            background-color: #0d6efd; /* Warna biru Bootstrap default */
            border-color: #0d6efd;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border-color: #f5c2c7;
            font-size: 14px;
        }

        .invalid-feedback {
            font-size: 13px;
        }
        
        .toggle-password {
            background: #e9ecef;
            border-left: none;
            color: #6c757d;
        }
        
        .toggle-password:hover {
            color: #495057;
            background: #e2e6ea;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #6c757d;
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 14px;
            height: 14px;
            border-width: 2px;
        }
        /* Tambahkan kelas shake agar tidak merusak style minimalis */
        .shake {
             animation: shake 0.4s;
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header text-center">
                <i class="bi bi-shield-lock fs-1 text-primary mb-2"></i>
                <h1>Login Admin</h1>
                <p>Silakan masukkan detail akun Anda.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Gagal!</strong> {{ $errors->first() }}
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="Alamat Email" 
                            required 
                            autofocus
                            value="{{ old('email') }}">
                    </div>
                    <div class="invalid-feedback">
                        <i class="bi bi-info-circle me-1"></i>Masukkan email yang valid
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Password" 
                            required>
                        <button 
                            type="button" 
                            class="btn toggle-password" 
                            id="togglePassword" 
                            title="Tampilkan/Sembunyikan password"
                            tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        <i class="bi bi-info-circle me-1"></i>Password tidak boleh kosong
                    </div>
                </div>

                <button 
                    id="submitBtn" 
                    type="submit" 
                    class="btn btn-primary btn-login mt-3">
                    <span id="btnText">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </span>
                </button>
            </form>
            
            <div class="login-footer">
                <a href="{{ route('beranda') }}" class="text-decoration-none">
                    <i class="bi bi-question-circle me-1"></i>Kembali ke Beranda
                </a>
                <p class="mt-2 mb-0">
                    <small>&copy; {{ date('Y') }} Bual Kawan.</small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('loginForm');
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const card = document.querySelector('.login-card');

            // Toggle password visibility
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove(isHidden ? 'bi-eye' : 'bi-eye-slash');
                icon.classList.add(isHidden ? 'bi-eye-slash' : 'bi-eye');
            });

            // Form validation and loading state
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    card.classList.add('shake');
                    setTimeout(() => card.classList.remove('shake'), 400);
                    
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                    return false;
                }

                // Disable button & show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-secondary'); // Ubah warna saat loading
                submitBtn.classList.remove('btn-primary');
                btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
            });

            // Prefill email jika ada error sebelumnya
            if (form.classList.contains('was-validated')) {
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        })();
    </script>
</body>
</html>