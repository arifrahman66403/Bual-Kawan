<x-layout-admin title="Manajemen Galeri">

  <h2 class="mb-4 fw-bold text-color">Manajemen Galeri Foto 🖼️</h2>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card p-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
      <h5 class="fw-bold text-color mb-2 mb-md-0">Daftar Foto Tersimpan</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Gambar
        </a>
        <button onclick="window.location.reload()" class="btn btn-sm btn-outline-secondary">
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
          <tr>
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
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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

</x-layout-admin>