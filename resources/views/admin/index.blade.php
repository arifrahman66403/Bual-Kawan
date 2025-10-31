<x-layout title="Riwayat Tracking">
<div class="container py-4">
  <h3 class="fw-bold mb-4">Riwayat Perubahan Status Kunjungan</h3>

  <table class="table table-striped table-bordered align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>Nama Instansi</th>
        <th>Status</th>
        <th>Catatan</th>
        <th>Diperbarui Oleh</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($trackings as $no => $t)
        <tr>
          <td>{{ $no+1 }}</td>
          <td>{{ $t->pengunjung->nama_instansi ?? '-' }}</td>
          <td>
            <span class="badge 
              @if($t->status=='pengajuan') bg-secondary 
              @elseif($t->status=='disetujui') bg-success 
              @elseif($t->status=='kunjungan') bg-info 
              @else bg-dark @endif">
              {{ ucfirst($t->status) }}
            </span>
          </td>
          <td>{{ $t->catatan ?? '-' }}</td>
          <td>{{ $t->createdBy->nama ?? 'System' }}</td>
          <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y H:i') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</x-layout>
