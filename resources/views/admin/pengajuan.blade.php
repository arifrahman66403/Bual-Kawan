<?php
/**
 * @file pengajuan.php
 * @author ziedanet
 * @description Halaman daftar pengajuan kunjungan dan fitur filter status.
 */
// Contoh simulasi data otentikasi.
$is_authenticated = true;
$admin_name = "Admin Bual Kawan";
$admin_avatar = "https://i.pravatar.cc/150?img=68";

if (!$is_authenticated) {
    header("Location: login.php");
    exit();
}

// Data Dummy Pengajuan Kunjungan
$data_pengajuan = [
    [
        'id' => 1,
        'pengaju' => 'Muhammad Raihan',
        'instansi' => 'PT Sinar Jaya',
        'tujuan' => 'Rapat Proyek Strategis',
        'tanggal' => '27 Okt 2025',
        'waktu' => '10:00',
        'status' => 'Menunggu',
        'urgensi' => 'Tinggi'
    ],
    [
        'id' => 2,
        'pengaju' => 'Tim Dev Siak',
        'instansi' => 'Dinas Kominfo',
        'tujuan' => 'Review Aplikasi Bual Kawan',
        'tanggal' => '26 Okt 2025',
        'waktu' => '14:30',
        'status' => 'Disetujui',
        'urgensi' => 'Normal'
    ],
    [
        'id' => 3,
        'pengaju' => 'Siti Aisyah',
        'instansi' => 'Individu',
        'tujuan' => 'Konsultasi Izin Usaha',
        'tanggal' => '25 Okt 2025',
        'waktu' => '09:00',
        'status' => 'Ditolak',
        'urgensi' => 'Rendah'
    ],
    [
        'id' => 4,
        'pengaju' => 'PT Maju Mundur',
        'instansi' => 'PT Maju Mundur',
        'tujuan' => 'Penawaran Kerjasama Proyek',
        'tanggal' => '27 Okt 2025',
        'waktu' => '11:30',
        'status' => 'Menunggu',
        'urgensi' => 'Tinggi'
    ],
    [
        'id' => 5,
        'pengaju' => 'Bambang Sudiro',
        'instansi' => 'Universitas Riau',
        'tujuan' => 'Studi Banding Sistem Admin',
        'tanggal' => '28 Okt 2025',
        'waktu' => '13:00',
        'status' => 'Menunggu',
        'urgensi' => 'Normal'
    ]
];

// Fungsi bantu untuk mendapatkan badge status
function get_status_badge($status) {
    switch ($status) {
        case 'Disetujui':
            return '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>';
        case 'Ditolak':
            return '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>';
        default:
            return '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass"></i> Menunggu</span>';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bual Kawan - Pengajuan Kunjungan</title>
    
    <link rel="icon" type="image/png" href="https://bualkawan.siakkab.go.id/logo-bualkawan2.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="assets/dashboard/css/style.css">

</head>
<body id="body">

<?php 
    include 'partisi/dashboard/navbar.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <?php 
            include 'partisi/dashboard/menu.php'; 
        ?>
    </div>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="pengajuan" role="tabpanel">
            <h2 class="mb-4 fw-bold text-color">Daftar Pengajuan Kunjungan 📋</h2>
            
            <div class="card p-3 mb-4 filter-status-box">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="fw-semibold text-color me-2 d-none d-md-inline">Filter Status:</span>
                    
                    <button class="btn btn-sm btn-genz active" data-filter="Semua">
                        <i class="bi bi-list-ul"></i> Semua (5)
                    </button>
                    
                    <button class="btn btn-sm btn-outline-warning text-dark" data-filter="Menunggu">
                        <i class="bi bi-hourglass"></i> Menunggu (3)
                    </button>
                    
                    <button class="btn btn-sm btn-outline-success" data-filter="Disetujui">
                        <i class="bi bi-check-circle"></i> Disetujui (1)
                    </button>
                    
                    <button class="btn btn-sm btn-outline-danger" data-filter="Ditolak">
                        <i class="bi bi-x-circle"></i> Ditolak (1)
                    </button>
                </div>
            </div>

            <div class="card p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <h5 class="fw-bold text-color mb-2 mb-md-0">Data Pengajuan Terbaru</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Export Excel</button>
                        <button class="btn btn-sm btn-genz"><i class="bi bi-plus-circle"></i> Tambah Manual</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-borderless pengajuan-table" id="dataPengajuan">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pengaju / Instansi</th>
                                <th class="d-none d-md-table-cell">Tujuan Kunjungan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Status</th>
                                <th class="d-none d-lg-table-cell">Urgensi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pengajuan as $data): ?>
                            <tr data-status="<?= $data['status'] ?>">
                                <td><?= $data['id'] ?></td>
                                <td data-label="Pengaju">
                                    <span class="fw-semibold text-color"><?= $data['pengaju'] ?></span>
                                    <div class="text-muted-genz d-block d-md-none"><?= $data['instansi'] ?></div>
                                    <div class="text-muted-genz d-none d-md-block"><?= $data['instansi'] ?></div>
                                </td>
                                <td class="d-none d-md-table-cell"><?= $data['tujuan'] ?></td>
                                <td data-label="Waktu">
                                    <?= $data['tanggal'] ?> 
                                    <div class="text-muted-genz"><?= $data['waktu'] ?></div>
                                </td>
                                <td data-label="Status">
                                    <?= get_status_badge($data['status']) ?>
                                </td>
                                <td class="d-none d-lg-table-cell" data-label="Urgensi">
                                    <?php 
                                        $color = match ($data['urgensi']) {
                                            'Tinggi' => 'danger',
                                            'Normal' => 'info',
                                            default => 'secondary',
                                        };
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= $data['urgensi'] ?></span>
                                </td>
                                <td data-label="Aksi" class="text-nowrap">
                                    <button class="btn btn-sm btn-genz" title="Lihat Detail"><i class="bi bi-eye"></i></button>

                                    <?php if ($data['status'] == 'Menunggu'): ?>
                                    <button class="btn btn-sm btn-success ms-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#actionModal"
                                            data-id="<?= $data['id'] ?>"
                                            data-name="<?= $data['pengaju'] ?>"
                                            data-action="terima"
                                            title="Setujui">
                                        <i class="bi bi-check2-circle"></i> Terima
                                    </button>
                                    <button class="btn btn-sm btn-danger ms-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#actionModal"
                                            data-id="<?= $data['id'] ?>"
                                            data-name="<?= $data['pengaju'] ?>"
                                            data-action="tolak"
                                            title="Tolak">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-secondary ms-1" title="Tidak Ada Aksi Lagi" disabled><i class="bi bi-lock"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul class="pagination justify-content-center mt-3">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="tab-pane fade" id="profil" role="tabpanel">
            </div>
        
    </div>
</div>

<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="actionModalLabel">Konfirmasi Aksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="action-icon mb-3">
                    <i id="modalIcon" class="bi fs-1"></i>
                </div>
                <p id="modalMessage" class="fs-5 fw-semibold"></p>
                <p class="text-muted">ID Pengajuan: <span id="modalIdDisplay" class="fw-bold"></span></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="modalConfirmButton" class="btn">Lanjutkan</a>
            </div>
        </div>
    </div>
</div>

<?php 
    include 'partisi/dashboard/footer.php'; 
?>

<?php 
    include 'partisi/dashboard/menu-mobile.php'; 
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="assets/dashboard/js/script.js"></script>

</body>
</html>