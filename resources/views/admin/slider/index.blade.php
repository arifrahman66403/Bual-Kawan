<x-layout-admin title="Manajemen Slider">

  <h2 class="mb-4 fw-bold text-color">Manajemen Slider / Banner 🎡</h2>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card p-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
      <h5 class="fw-bold text-color mb-2 mb-md-0">Daftar Slider Aktif</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.slider.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Slider
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
            <th>No</th>
            <th style="width: 200px;">Preview Banner</th>
            <th>Judul Slider</th>
            <th>Keterangan</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sliders as $no => $item)
          <tr>
            <td>{{ $sliders->firstItem() + $no }}</td>
            <td>
                <img src="{{ asset('storage/' . $item->image) }}" 
                     alt="Slider Img" 
                     class="img-fluid rounded shadow-sm" 
                     style="height: 60px; width: 150px; object-fit: cover;">
            </td>
            <td>
              <span class="fw-bold text-color">{{ $item->title }}</span>
              <br>
              <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
            </td>
            <td>{{ $item->description ?? '-' }}</td>
            <td class="text-end">
                <form onsubmit="return confirm('Yakin hapus slider ini?');" 
                      action="{{ route('admin.slider.destroy', $item->id) }}" 
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
                <i class="bi bi-tv fs-3 d-block mb-2"></i>
                Belum ada slider/banner yang dipasang.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
      {{ $sliders->links() }}
    </div>

  </div>

</x-layout-admin>