    <div class="container py-4">
        <h3 class="mb-4 fw-bold">Tracking Kunjungan</h3>

        <form action="{{ route('tracking.scan') }}" method="POST" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="text" name="kode_qr" class="form-control" placeholder="Masukkan kode QR...">
                <button class="btn btn-primary" type="submit">Scan</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Pengunjung</th>
                    <th>Status</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trackings as $track)
                <tr>
                    <td>{{ $track->pengunjung->nama }}</td>
                    <td>{{ ucfirst($track->status) }}</td>
                    <td>{{ $track->waktu_masuk ?? '-' }}</td>
                    <td>{{ $track->waktu_keluar ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
