<x-layout title="Log Aktivitas">
<div class="container py-4">
  <h3 class="fw-bold mb-4">Log Aktivitas Sistem</h3>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-primary">
      <tr>
        <th>No</th>
        <th>User</th>
        <th>Pengunjung</th>
        <th>Aksi</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($logs as $no => $log)
        <tr>
          <td>{{ $no+1 }}</td>
          <td>{{ $log->user->nama ?? '-' }}</td>
          <td>{{ $log->pengunjung->nama_instansi ?? '-' }}</td>
          <td>{{ $log->aksi }}</td>
          <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
</x-layout>
