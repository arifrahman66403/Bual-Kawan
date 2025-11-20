<x-layout-admin title="Manajemen Admin">

  <h2 class="mb-4 fw-bold text-color">Manajemen Administrator 👥</h2>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card p-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
      <h5 class="fw-bold text-color mb-2 mb-md-0">Daftar Pengguna Sistem</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </a>
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
             <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama/user..." value="{{ request('search') }}">
             <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle table-borderless">
        <thead class="table-primary">
          <tr>
            <th>No</th>
            <th>Nama & Kontak</th>
            <th>Username / Role</th>
            <th>Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $no => $user)
          <tr>
            <td>{{ $users->firstItem() + $no }}</td>
            <td>
              <span class="fw-bold text-color">{{ $user->nama }}</span><br>
              <small class="text-muted">{{ $user->email }}</small>
              @if($user->wa) 
                <br><small class="text-success"><i class="bi bi-whatsapp"></i> {{ $user->wa }}</small> 
              @endif
            </td>
            <td>
                <div class="d-flex flex-column">
                    <span class="fw-semibold">@ {{ $user->user }}</span>
                    <span class="badge bg-secondary align-self-start mt-1">{{ strtoupper($user->role) }}</span>
                </div>
            </td>
            <td>
                @if($user->is_active == 1)
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                @else
                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Non-Aktif</span>
                @endif
            </td>
            <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                    
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            <i class="bi {{ $user->is_active ? 'bi-slash-circle' : 'bi-check-lg' }}"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info text-white" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form onsubmit="return confirm('Yakin ingin menghapus user ini?');" 
                          action="{{ route('admin.users.destroy', $user->id) }}" 
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Tidak ada data administrator.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
      {{ $users->links() }}
    </div>
  </div>
</x-layout-admin>