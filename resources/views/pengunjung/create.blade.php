<x-layout title="Pengajuan Tamu">
<div class="container py-4">
  <h3 class="fw-bold mb-4">Form Pengajuan Tamu</h3>

  <form action="{{ route('pengunjung.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Nama Instansi</label>
      <input type="text" name="nama_instansi" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Satuan Kerja</label>
      <input type="text" name="satuan_kerja" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Tujuan</label>
      <input type="text" name="tujuan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Tanggal Kunjungan</label>
      <input type="date" name="tgl_kunjungan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nama Perwakilan</label>
      <input type="text" name="nama_perwakilan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email Perwakilan</label>
      <input type="email" name="email_perwakilan" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>No WA Perwakilan</label>
      <input type="text" name="wa_perwakilan" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
  </form>
</div>
</x-layout>
