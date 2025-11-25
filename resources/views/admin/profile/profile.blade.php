<x-layout-admin title="Profil Saya">
    <div class="container py-4 py-md-5">
        <div class="row g-4">

            {{-- KOLOM KIRI: KARTU PROFIL --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center h-100">
                    <div class="card-body p-4">
                        {{-- Avatar: Gunakan UI Avatar (Initials) karena DB tidak simpan foto --}}
                        <div class="mb-3 position-relative d-inline-block">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=0d6efd&color=fff&size=128" 
                                 class="rounded-circle img-thumbnail shadow-sm" 
                                 style="width: 120px; height: 120px;" 
                                 alt="Avatar">
                            
                            {{-- Badge Role --}}
                            <span class="position-absolute bottom-0 start-100 translate-middle badge rounded-pill bg-warning text-dark border border-light">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>

                        {{-- TAMPILKAN NAMA (Kolom: nama) --}}
                        <h5 class="fw-bold mb-1">{{ Auth::user()->nama }}</h5>
                        {{-- TAMPILKAN USERNAME (Kolom: user) --}}
                        <p class="text-muted mb-1">@ {{ Auth::user()->user }}</p>
                        <p class="text-secondary small mb-3">{{ Auth::user()->email }}</p>

                        <hr>
                        
                        {{-- Info Tambahan --}}
                        <div class="text-start">
                            <ul class="list-unstyled mt-2 mb-0 small text-secondary">
                                <li class="mb-2">
                                    <i class="bi bi-whatsapp me-2 text-success"></i> 
                                    {{ Auth::user()->wa ?? '-' }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-calendar-check me-2 text-primary"></i> 
                                    Bergabung: {{ Auth::user()->created_at->format('d M Y') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM EDIT --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <ul class="nav nav-tabs card-header-tabs" id="profileTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button">Edit Profil</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">Ganti Password</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        {{-- Flash Messages --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0 ps-3">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="tab-content">
                            {{-- TAB 1: EDIT PROFIL --}}
                            <div class="tab-pane fade show active" id="edit">
                                <form action="{{ route('admin.profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama" value="{{ old('nama', Auth::user()->nama) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Username</label>
                                            <input type="text" class="form-control" name="user" value="{{ old('user', Auth::user()->user) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email', Auth::user()->email) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">No. WhatsApp</label>
                                        <input type="text" class="form-control" name="wa" value="{{ old('wa', Auth::user()->wa) }}" placeholder="08...">
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>

                            {{-- TAB 2: GANTI PASSWORD --}}
                            <div class="tab-pane fade" id="password">
                                <form action="{{ route('admin.profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Password Saat Ini</label>
                                        <input type="password" class="form-control" name="current_password">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Password Baru</label>
                                            <input type="password" class="form-control" name="new_password">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-bold">Konfirmasi Password</label>
                                            <input type="password" class="form-control" name="new_password_confirmation">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-shield-lock me-1"></i> Perbarui Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>