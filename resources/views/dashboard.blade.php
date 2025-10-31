<x-layout>

<div class="container-fluid py-5">
    
    <x-desktop-nav />

    <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
        <h2 class="mb-4 fw-bold text-color">Statistik Kunjungan $Flash$ ✨</h2>
        
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-1">
                    <i class="bi bi-calendar-check fs-4 mb-2"></i>
                    <div class="big-number">45</div>
                    <div class="text-muted-genz">Total Tamu Hari Ini</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-2">
                    <i class="bi bi-graph-up fs-4 mb-2"></i>
                    <div class="big-number">+12%</div>
                    <div class="text-muted-genz">Kenaikan Kunjungan (vs $L$ Week)</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-3">
                    <i class="bi bi-hourglass-split fs-4 mb-2"></i>
                    <div class="big-number">15 Min</div>
                    <div class="text-muted-genz">Rata-rata Durasi Tamu</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="card p-3 stat-card-bg stat-card-4">
                    <i class="bi bi-people fs-4 mb-2"></i>
                    <div class="big-number">3.2K</div>
                    <div class="text-muted-genz">Total Tamu Sepanjang Masa</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold text-color">Tren Kunjungan 7 Hari Terakhir</h5>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold text-color">Distribusi Tujuan</h5>
                    <canvas id="donutChart"></canvas>
                    <div class="mt-3 text-center text-muted-genz">Data berdasarkan 500 kunjungan terakhir.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="profil" role="tabpanel">
        <h2 class="mb-4 fw-bold text-color">Profil Pribadi 👤</h2>
        <div class="card p-4 col-lg-6 mx-auto">
            <div class="text-center mb-4">
                <img src="https://i.pravatar.cc/150?img=68" class="rounded-circle avatar-lg mb-2" alt="Foto Profil">
                <h4 class="fw-bold mb-0 text-color">Admin Bual Kawan</h4>
                <p class="text-muted-genz">Super Admin</p>
            </div>
            <form>
                <div class="mb-3">
                    <label for="namaLengkap" class="form-label text-color">Nama Lengkap</label>
                    <input type="text" class="form-control" id="namaLengkap" value="Admin Bual Kawan" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-color">Email</label>
                    <input type="email" class="form-control" id="email" value="admin@bualkawan.id" readonly>
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label text-color">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="telepon" value="08123456789">
                </div>
                <button type="submit" class="btn btn-genz w-100 mt-3">Simpan Perubahan</button>
                <button type="button" class="btn btn-outline-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Keluar / Log Out</button>
            </form>
        </div>
    </div>
</div>

</x-layout>
