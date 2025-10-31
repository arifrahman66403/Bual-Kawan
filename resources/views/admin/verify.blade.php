<x-layout title="Verifikasi Pengunjung">
<div class="container py-4">
  <h3 class="fw-bold mb-4">Daftar Pengunjung - Verifikasi</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Instansi</th>
        <th>Perwakilan</th>
        <th>Tujuan</th>
        <th>Tanggal Kunjungan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pengunjungs as $no => $p)
        <tr>
          <td>{{ $no+1 }}</td>
          <td>{{ $p->nama_instansi }}</td>
          <td>{{ $p->nama_perwakilan }}</td>
          <td>{{ $p->tujuan }}</td>
          <td>{{ \Carbon\Carbon::parse($p->tgl_kunjungan)->format('d M Y') }}</td>
          <td>
            <span class="badge 
              @if($p->status=='pengajuan') bg-secondary 
              @elseif($p->status=='disetujui') bg-success 
              @elseif($p->status=='kunjungan') bg-info 
              @else bg-dark @endif">
              {{ ucfirst($p->status) }}
            </span>
          </td>
          <td>
            <form action="{{ route('pengunjung.status', $p->uid) }}" method="POST" class="d-flex gap-1">
              @csrf
              <select name="status" class="form-select form-select-sm" required>
                <option value="">Pilih</option>
                <option value="disetujui">Disetujui</option>
                <option value="kunjungan">Kunjungan</option>
                <option value="selesai">Selesai</option>
              </select>
              <button type="submit" class="btn btn-sm btn-primary">Ubah</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</x-layout>
