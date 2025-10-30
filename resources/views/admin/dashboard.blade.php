<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Admin Dashboard</span>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="btn btn-outline-light">Logout</button>
    </form>
  </nav>

  <div class="container mt-5">
    <h2>Selamat datang, {{ Auth::guard('admin')->user()->name }}</h2>
    <p>Ini adalah halaman dashboard admin.</p>
  </div>
</body>
</html>
