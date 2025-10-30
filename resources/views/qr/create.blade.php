<x-layout title="Buat QR Code">
    <div class="container py-4">
        <h3 class="mb-4 fw-bold text-center">Buat QR Code Baru</h3>

        <form action="{{ route('qr.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Pengunjung</label>
                <select name="pengunjung_id" class="form-select" required>
                    <option value="">-- Pilih Pengunjung --</option>
                    @foreach($pengunjung as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Berlaku Mulai</label>
                <input type="date" name="berlaku_mulai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Berlaku Sampai</label>
                <input type="date" name="berlaku_sampai" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Generate QR</button>
        </form>
    </div>
</x-layout>
