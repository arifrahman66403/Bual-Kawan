<x-layout-admin title="Manajemen Galeri">

  <style>
    /* Card fade-in */
    .fade-card { animation: fadeUp .35s ease both; }
    /* Row animation */
    .animated-row { animation: fadeUp .25s ease both; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(6px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Table row hover */
    .table-hover tbody tr:hover { background-color: #f8fafc; }

    /* Image hover zoom */
    .img-thumbnail {
      transition: transform .25s ease, box-shadow .25s ease;
      cursor: pointer;
    }
    .img-thumbnail:hover {
      transform: scale(1.07);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Small transition for buttons */
    .btn-transition { transition: transform .12s ease; }
    .btn-transition:active { transform: translateY(1px); }
  </style>

  <h2 class="mb-4 fw-bold text-color">Manajemen Galeri Foto 🖼️</h2>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card p-4 fade-card">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
      <h5 class="fw-bold text-color mb-2 mb-md-0">Daftar Foto Tersimpan</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm btn-primary btn-transition" data-bs-toggle="tooltip" title="Tambah Gambar">
            <i class="bi bi-plus-lg"></i> Tambah Gambar
        </a>
        <button onclick="window.location.reload()" class="btn btn-sm btn-outline-secondary btn-transition" data-bs-toggle="tooltip" title="Refresh daftar">
          <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle table-borderless">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th style="width: 150px;">Preview</th>
            <th>Judul & Deskripsi</th>
            <th>Tanggal Upload</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($galleries as $no => $item)
          <tr class="animated-row">
            <td>{{ $galleries->firstItem() + $no }}</td>
            <td>
                <img src="{{ asset('storage/' . $item->image) }}" 
                     alt="Img" 
                     class="img-thumbnail rounded" 
                     style="height: 80px; width: 120px; object-fit: cover;">
            </td>
            <td>
              <span class="fw-bold text-color d-block">{{ $item->title }}</span>
              <small class="text-muted">{{ Str::limit($item->description, 50) ?? '-' }}</small>
            </td>
            <td>
                {{ $item->created_at->format('d M Y') }} <br>
                <small class="text-muted">{{ $item->created_at->format('H:i') }} WIB</small>
            </td>
            <td class="text-end">
                <form onsubmit="return confirm('Yakin ingin menghapus gambar ini?');" 
                      action="{{ route('admin.gallery.destroy', $item->id) }}" 
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus" data-bs-toggle="tooltip">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
                <i class="bi bi-images fs-3 d-block mb-2"></i>
                Belum ada data galeri.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
      {{ $galleries->links() }}
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Inisialisasi tooltip Bootstrap jika tersedia
      if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
          new bootstrap.Tooltip(el);
        });
      }
    });
  </script>

</x-layout-admin>