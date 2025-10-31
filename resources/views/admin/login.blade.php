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
      background: rgba(33, 37, 41, 0.8);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      transform: translateY(20px);
      animation: float 0.8s ease-out forwards;
    }
    
    @keyframes float {
      to {
        transform: translateY(0);
      }
    }

    .form-control {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: white;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: rgba(255,255,255,0.15);
      border-color: #fff;
      box-shadow: 0 0 10px rgba(255,255,255,0.2);
      color: white;
    }

    .btn-login {
      background: #0d6efd;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(13,110,253,0.4);
    }

    body {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
  </style>
</head>
<body class="bg-dark text-light d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4 bg-secondary" style="width: 400px;">
    <h3 class="text-center mb-3">Login Admin</h3>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="text-light">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="text-light">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif
      <button type="submit" class="btn btn-login text-light w-100">Login</button>
    </form>
  </div>
</body>
</html>
