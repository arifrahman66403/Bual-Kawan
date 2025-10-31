<x-head title="Bual Kawan - Dashboard" />

<x-navbar />

<div class="container-fluid py-5">
    
    <x-desktop-nav />
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
<x-bottom-nav />

<x-footer />

<x-scripts />