<x-layout-admin title="Edit Admin">
    <h2 class="mb-4 fw-bold text-color">Edit Administrator</h2>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h5 class="fw-bold text-color mb-0">Edit Data User</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="user" class="form-control" value="{{ old('user', $user->user) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. WhatsApp</label>
                    <input type="number" name="wa" class="form-control" value="{{ old('wa', $user->wa) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password Baru</label>
                    <input type="password" name="pass" class="form-control" placeholder="Kosongkan jika tidak ingin merubah password">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i> Update Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layout-admin>