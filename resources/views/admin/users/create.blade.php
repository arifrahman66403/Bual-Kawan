<x-layout-admin title="Tambah Admin">
    <h2 class="mb-4 fw-bold text-color">Tambah Administrator Baru</h2>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h5 class="fw-bold text-color mb-0">Form User</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="user" class="form-control" value="{{ old('user') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. WhatsApp</label>
                    <input type="number" name="wa" class="form-control" value="{{ old('wa') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="pass" class="form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</x-layout-admin>