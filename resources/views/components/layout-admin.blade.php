<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Singgah - Dashboard Admin</title>
    
    <link rel="icon" type="image/png" href="https://bualkawan.siakkab.go.id/logo-bualkawan2.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body id="body">

<x-navbar-admin></x-navbar-admin>

<div class="container-fluid py-4">
    <x-desktop-nav-admin />

    <div class="tab-content">

        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
            {{ $slot }}
        </div>
        
    </div>
</div>

<x-footer-admin></x-footer-admin>

<x-bottom-nav-admin />

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-body p-4">
        <div class="logout-icon-box mx-auto">
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <h5 class="fw-bold text-color mb-2">Yakin Ingin Keluar?</h5>
        <p class="text-muted-genz">Sesi Anda akan diakhiri. Anda perlu login kembali.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 p-3">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="{{ route('logout') }}" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>