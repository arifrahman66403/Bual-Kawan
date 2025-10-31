/**
 * @file script.js
 * @author ziedanet
 * @description Skrip utama untuk Dark Mode, Chart.js initialization, dan Table Filtering.
 */

// --- JavaScript untuk Interaksi & Grafik ---

// Fungsi untuk mengaktifkan tab (Hanya Profil)
function setActiveNav(targetId) {
    document
        .querySelectorAll(".tab-pane")
        .forEach((pane) => pane.classList.remove("show", "active"));
    const targetPane = document.getElementById(targetId);
    if (targetPane) {
        targetPane.classList.add("show", "active");
    }
}

// --- Dark Mode Toggle Logic ---
const darkModeToggle = document.getElementById("darkModeToggle");
const body = document.getElementById("body");
const modeIcon = document.getElementById("modeIcon");

darkModeToggle.addEventListener("click", () => {
    body.classList.toggle("dark-mode");

    if (body.classList.contains("dark-mode")) {
        modeIcon.classList.remove("bi-sun");
        modeIcon.classList.add("bi-moon-stars");
        localStorage.setItem("theme", "dark");
    } else {
        modeIcon.classList.remove("bi-moon-stars");
        modeIcon.classList.add("bi-sun");
        localStorage.setItem("theme", "light");
    }

    initializeCharts();
    updateDynamicStyles();
});

// Fungsi untuk memperbarui style dinamis (misalnya border avatar)
function updateDynamicStyles() {
    const isDarkMode = body.classList.contains("dark-mode");
    const navbarTextColor = getComputedStyle(document.documentElement)
        .getPropertyValue("--navbar-text")
        .trim();

    const avatar = document.querySelector(".avatar-sm");
    if (avatar) {
        avatar.style.borderColor = navbarTextColor;
    }
}

// Cek preferensi tema saat memuat halaman
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "dark") {
    body.classList.add("dark-mode");
    modeIcon.classList.remove("bi-sun");
    modeIcon.classList.add("bi-moon-stars");
}

// --- Fungsi untuk inisialisasi Chart.js ---
function initializeCharts() {
    const isDarkMode = body.classList.contains("dark-mode");
    const textColor = isDarkMode ? "#f5f7fa" : "#212529";
    const gridColor = isDarkMode
        ? "rgba(255, 255, 255, 0.1)"
        : "rgba(0, 0, 0, 0.1)";
    const chartColors = ["#6c5ce7", "#ff6b81", "#1dd1a1", "#ff9f43", "#00cec9"];

    if (typeof Chart === "undefined") return;

    if (window.lineChartInstance) window.lineChartInstance.destroy();
    if (window.donutChartInstance) window.donutChartInstance.destroy();

    // 1. Line Chart
    const lineCtx = document.getElementById("lineChart");
    if (lineCtx) {
        const gradient = lineCtx
            .getContext("2d")
            .createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, "rgba(108, 92, 231, 0.5)");
        gradient.addColorStop(1, "rgba(108, 92, 231, 0)");

        window.lineChartInstance = new Chart(lineCtx, {
            type: "line",
            data: {
                labels: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                datasets: [
                    {
                        label: "Kunjungan",
                        data: [25, 40, 35, 55, 60, 45, 30],
                        borderColor: chartColors[0],
                        backgroundColor: gradient,
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointBackgroundColor: chartColors[0],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor },
                    },
                },
            },
        });
    }

    // 2. Donut Chart
    const donutCtx = document.getElementById("donutChart");
    if (donutCtx) {
        window.donutChartInstance = new Chart(donutCtx, {
            type: "doughnut",
            data: {
                labels: [
                    "Rapat Koordinasi",
                    "Pelatihan",
                    "Kerja Sama",
                    "Lainnya",
                ],
                datasets: [
                    {
                        label: "Jumlah Kunjungan",
                        data: [45, 25, 20, 10],
                        backgroundColor: chartColors.slice(0, 4),
                        hoverOffset: 8,
                        borderWidth: 4,
                        borderColor: isDarkMode ? "#1f1d2e" : "#ffffff",
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            color: textColor,
                            font: { size: 14 },
                        },
                    },
                },
            },
        });
    }
}

// --- FUNGSI UNTUK FILTER TABEL PENGAJUAN ---
function initializeTableFilter() {
    const filterButtons = document.querySelectorAll(
        ".filter-status-box button"
    );
    const tableRows = document.querySelectorAll("#dataPengajuan tbody tr");

    if (filterButtons.length === 0 || tableRows.length === 0) {
        return;
    }

    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            filterButtons.forEach((btn) => {
                btn.classList.remove("active");
                if (btn.classList.contains("btn-genz")) {
                    btn.classList.remove("btn-genz");
                    btn.classList.add("btn-outline-secondary");
                }
                if (btn.getAttribute("data-filter") === "Menunggu") {
                    btn.classList.remove("text-dark");
                }
            });

            this.classList.add("active");
            if (this.classList.contains("btn-outline-secondary")) {
                this.classList.remove("btn-outline-secondary");
                this.classList.add("btn-genz");
            }
            if (this.getAttribute("data-filter") === "Menunggu") {
                this.classList.add("text-dark");
            }

            const filterValue = this.getAttribute("data-filter");

            tableRows.forEach((row) => {
                const rowStatus = row.getAttribute("data-status");

                if (filterValue === "Semua" || rowStatus === filterValue) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
}
// --- AKHIR FUNGSI FILTER TABEL PENGAJUAN ---

// --- FUNGSI BARU UNTUK KONFIRMASI MODAL AKSI ---
function initializeActionModal() {
    const actionModal = document.getElementById("actionModal");
    if (!actionModal) return;

    // Gunakan event listener native Bootstrap untuk mengisi konten modal
    actionModal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");
        const actionType = button.getAttribute("data-action");

        const modalTitle = document.getElementById("actionModalLabel");
        const modalMessage = document.getElementById("modalMessage");
        const modalIcon = document.getElementById("modalIcon");
        const modalIdDisplay = document.getElementById("modalIdDisplay");
        const modalConfirmButton =
            document.getElementById("modalConfirmButton");

        // Reset classes
        modalIcon.className = "bi fs-1";
        modalConfirmButton.className = "btn";

        if (actionType === "terima") {
            modalTitle.textContent = "Setujui Kunjungan";
            modalMessage.innerHTML = `Anda yakin ingin **MENYETUJUI** pengajuan dari **${name}**?`;
            modalIcon.classList.add("bi-check2-circle", "text-success");
            modalConfirmButton.classList.add("btn-success");
            // Ganti dengan URL PHP/Endpoint yang sebenarnya
            modalConfirmButton.href = `aksi_pengajuan.php?id=${id}&action=approve`;
        } else if (actionType === "tolak") {
            modalTitle.textContent = "Tolak Kunjungan";
            modalMessage.innerHTML = `Anda yakin ingin **MENOLAK** pengajuan dari **${name}**?`;
            modalIcon.classList.add("bi-x-circle", "text-danger");
            modalConfirmButton.classList.add("btn-danger");
            // Ganti dengan URL PHP/Endpoint yang sebenarnya
            modalConfirmButton.href = `aksi_pengajuan.php?id=${id}&action=reject`;
        }

        modalIdDisplay.textContent = id;
    });
}
// --- AKHIR FUNGSI KONFIRMASI MODAL AKSI ---

// Inisialisasi semua fungsi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    initializeCharts();
    updateDynamicStyles();
    initializeTableFilter();
    initializeActionModal(); // Panggil fungsi modal baru
});

// ... (Kode sebelumnya) ...

// --- FUNGSI BARU UNTUK KONFIRMASI MODAL AKSI USER (Admin.php) ---
function initializeUserActionModal() {
    const actionModal = document.getElementById("userActionModal");
    if (!actionModal) return;

    actionModal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute("data-id");
        const name = button.getAttribute("data-name");
        const actionType = button.getAttribute("data-action");

        const modalTitle = document.getElementById("userActionModalLabel");
        const modalMessage = document.getElementById("userModalMessage");
        const modalIcon = document.getElementById("userModalIcon");
        const modalIdDisplay = document.getElementById("userModalIdDisplay");
        const modalConfirmButton = document.getElementById(
            "userModalConfirmButton"
        );

        // Reset classes
        modalIcon.className = "bi fs-1";
        modalConfirmButton.className = "btn";

        if (actionType === "deactivate") {
            modalTitle.textContent = "Nonaktifkan Akun";
            modalMessage.innerHTML = `Anda yakin ingin **MENONAKTIFKAN** akun **${name}**?`;
            modalIcon.classList.add("bi-person-x", "text-warning");
            modalConfirmButton.classList.add("btn-warning", "text-dark");
            modalConfirmButton.href = `aksi_admin.php?id=${id}&action=deactivate`;
        } else if (actionType === "activate") {
            modalTitle.textContent = "Aktifkan Akun";
            modalMessage.innerHTML = `Anda yakin ingin **MENGAKTIFKAN** kembali akun **${name}**?`;
            modalIcon.classList.add("bi-person-check", "text-success");
            modalConfirmButton.classList.add("btn-success");
            modalConfirmButton.href = `aksi_admin.php?id=${id}&action=activate`;
        } else if (actionType === "delete") {
            modalTitle.textContent = "Hapus Akun Permanen";
            modalMessage.innerHTML = `**PERINGATAN!** Anda yakin ingin **MENGHAPUS** akun **${name}** secara permanen?`;
            modalIcon.classList.add("bi-trash", "text-danger");
            modalConfirmButton.classList.add("btn-danger");
            modalConfirmButton.href = `aksi_admin.php?id=${id}&action=delete`;
        }

        modalIdDisplay.textContent = id;
    });
}
// --- AKHIR FUNGSI KONFIRMASI MODAL AKSI USER ---

// Inisialisasi semua fungsi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    initializeCharts();
    updateDynamicStyles();
    initializeTableFilter(); // Untuk pengajuan.php
    initializeActionModal(); // Untuk pengajuan.php
    initializeUserActionModal(); // Untuk admin.php (NEW!)
});

// ... (Kode initializeActionModal dan initializeUserActionModal sebelumnya) ...

// --- FUNGSI BARU UNTUK MODAL FORM TAMBAH/EDIT ADMIN ---
function initializeAdminFormModal() {
    const formModal = document.getElementById("adminFormModal");
    if (!formModal) return;

    formModal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;
        const mode = button.getAttribute("data-mode"); // 'add' atau 'edit'
        const adminDataJson = button.getAttribute("data-admin-data");

        const modalTitle = document.getElementById("adminFormModalLabel");
        const submitButton = document.getElementById("submitButton");
        const form = document.getElementById("adminForm");

        // Form Fields
        const adminId = document.getElementById("adminId");
        const formMode = document.getElementById("formMode");
        const nama = document.getElementById("nama");
        const email = document.getElementById("email");
        const peran = document.getElementById("peran");
        const passwordInput = document.getElementById("password");
        const passwordLabel = document.getElementById("passwordLabel");
        const passwordHelp = document.getElementById("passwordHelp");

        // Reset Form
        form.reset();

        if (mode === "add") {
            // Mode Tambah Baru
            modalTitle.textContent = "Tambah Administrator Baru";
            submitButton.textContent = "Tambahkan Admin";
            formMode.value = "add";

            // Password wajib diisi saat tambah
            passwordLabel.textContent = "Password (Wajib)";
            passwordHelp.textContent = "Password harus diisi untuk admin baru.";
            passwordInput.required = true;
        } else if (mode === "edit") {
            // Mode Edit
            modalTitle.textContent = "Edit Detail Administrator";
            submitButton.textContent = "Simpan Perubahan";
            formMode.value = "edit";

            // Parsing dan Isi Data Admin
            const data = JSON.parse(adminDataJson);

            adminId.value = data.id;
            nama.value = data.nama;
            email.value = data.email;
            peran.value = data.peran;

            // Password bersifat opsional saat edit
            passwordInput.required = false;
            passwordInput.value = ""; // Kosongkan
            passwordLabel.textContent = "Ubah Password";
            passwordHelp.textContent =
                "Kosongkan jika tidak ingin mengubah password.";
        }
    });
}
// --- AKHIR FUNGSI MODAL FORM TAMBAH/EDIT ADMIN ---

// Inisialisasi semua fungsi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    initializeCharts();
    updateDynamicStyles();
    initializeTableFilter();
    initializeActionModal();
    initializeUserActionModal();
    initializeAdminFormModal(); // Panggil fungsi modal form baru
});
