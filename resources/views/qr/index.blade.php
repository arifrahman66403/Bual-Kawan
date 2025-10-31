<x-layout title="Bual Kawan - Dashboard">
    <div class="container py-4">
        <h3 class="mb-4 fw-bold">Daftar QR Code</h3>

        <a href="{{ route('qr.create') }}" class="btn btn-primary mb-3">+ Buat QR Baru</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode QR</th>
                    <th>QR Code</th>
                    <th>Berlaku</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qrCodes as $qr)
                <tr>
                    <td>{{ $qr->qr_code }}</td>
                    <td><img src="{{ asset($qr->path_qr) }}" width="100"></td>
                    <td>{{ $qr->berlaku_mulai }} - {{ $qr->berlaku_sampai }}</td>
                    <td>{{ ucfirst($qr->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>