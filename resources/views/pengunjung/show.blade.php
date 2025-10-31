<x-layout title="Detail Pengunjung">
<div class="container py-4">
  <h3 class="fw-bold mb-4">Detail Pengunjung</h3>

  <div class="card">
    <div class="card-body">
      <p><strong>Nama Instansi:</strong> {{ $pengunjung->nama_instansi }}</p>
      <p><strong>Tujuan:</strong> {{ $pengunjung->tujuan }}</p>
      <p><strong>Status:</strong> 
        <span class="badge bg-primary">{{ ucfirst($pengunjung->status) }}</span>
      </p>

      @if($pengunjung->qrCode)
        <p><strong>QR Code Kunjungan:</strong></p>
        <img src="{{ asset($pengunjung->qrCode->qr_code) }}" alt="QR Code" width="200">
      @endif
    </div>
  </div>
</div>
</x-layout>
