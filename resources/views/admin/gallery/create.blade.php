<x-layout-admin title="Tambah Galeri">

    <h2 class="mb-4 fw-bold text-color">Upload Gambar Baru 📤</h2>

    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h5 class="fw-bold text-color mb-0">Form Upload</h5>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Judul Gambar <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Masukkan judul..." value="{{ old('title') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Slug (Opsional)</label>
                    <input type="text" name="slug" class="form-control" placeholder="Masukkan slug..." value="{{ old('slug') }}">
                    <div class="form-text text-muted">Jika dikosongkan, slug akan dibuat otomatis dari judul.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">File Gambar <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    <div class="form-text text-muted">Format: JPG, PNG, JPEG. Maks: 2MB.</div>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tambahkan keterangan...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Gambar
                </button>
            </div>
        </form>
    </div>

</x-layout-admin>