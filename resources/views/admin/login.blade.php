<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Bual Kawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
        }

        .login-header .logo-box {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
            font-weight: 400;
        }

        .login-body {
            padding: 45px 35px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #667eea;
            font-size: 18px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control {
            border: 1.5px solid #e2e8f0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            color: #2d3748;
            background: #f7fafc;
        }

        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            color: #2d3748;
        }

        .form-control::placeholder {
            color: #a0aec0;
            font-weight: 400;
        }

        .input-group:focus-within .input-group-text {
            border-color: #667eea;
            background: #f7fafc;
        }

        .toggle-password {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 12px 15px;
            font-size: 16px;
        }

        .toggle-password:hover {
            color: #764ba2;
            background: #f7fafc;
        }

        .toggle-password:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
            border: none;
            padding: 12px 16px;
        }

        .alert-danger {
            background: #fed7d7;
            color: #742a2a;
        }

        .invalid-feedback {
            font-size: 12px;
            margin-top: 6px;
            color: #e53e3e;
            display: block;
        }

        .shake {
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }

        .login-footer {
            text-align: center;
            padding: 20px 35px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #718096;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-body {
                padding: 35px 25px;
            }

            .login-header {
                padding: 40px 25px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-header .logo-box {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }

            .login-footer {
                padding: 15px 25px;
            }
        }

        /* Loading spinner */
        .spinner-border-sm {
            width: 14px;
            height: 14px;
            border-width: 2px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-box">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1>Bual Kawan</h1>
                <p>Admin Portal</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Error Message -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Login Gagal!</strong> {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form -->
                <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-at"></i>
                            </span>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                placeholder="admin@example.com" 
                                required 
                                autofocus
                                value="{{ old('email') }}">
                        </div>
                        <div class="invalid-feedback">
                            <i class="bi bi-info-circle me-1"></i>Masukkan email yang valid
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                placeholder="Masukkan password" 
                                required>
                            <button 
                                type="button" 
                                class="toggle-password" 
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

                            <!-- ✅ reCAPTCHA -->
            <div class="mt-4">
                <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                @error('g-recaptcha-response')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

                    <!-- Submit Button -->
                    <button 
                        id="submitBtn" 
                        type="submit" 
                        class="btn btn-login">
                        <span id="btnText">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p class="mb-2">
                    <i class="bi bi-question-circle me-1"></i>
                    Kembali ke <a href="/">Beranda</a>
                </p>
                <small>© {{ date('Y') }} Bual Kawan. All rights reserved.</small>
            </div>
        </div>
    </div>

    <!-- ✅ reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Bootstrap JS -->
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

            // Form validation
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
