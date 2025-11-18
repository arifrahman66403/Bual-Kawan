<x-layout title="Input Peserta Rombongan">
    <div class="container my-5">
        <h2 class="fw-bold text-color mb-3">Selamat Datang, {{ $pengunjung->nama_perwakilan }}!</h2>
        <p class="lead">Silakan input data peserta rombongan Anda untuk kunjungan dari **{{ $pengunjung->nama_instansi }}** pada tanggal **{{ Carbon\Carbon::parse($pengunjung->tgl_kunjungan)->format('d F Y') }}**.</p>
        
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card p-4 shadow-sm">
            <h5 class="fw-bold mb-4">Formulir Peserta Rombongan</h5>

            <form action="{{ route('pengunjung.store.peserta', $pengunjung->uid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div id="peserta-list">
                    <script>
                        // Panggil fungsi addPesertaRow() saat DOM ready (JS di bawah)
                    </script>
                </div>
                
                <button type="button" id="add-peserta-btn" class="btn btn-outline-genz mt-3">
                    <i class="bi bi-plus-circle"></i> Tambah Peserta Lagi
                </button>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-send"></i> Selesai & Kirim Data Rombongan
                </button>
            </form>
        </div>
    </div>
    
    <template id="peserta-row-template">
        <div class="row g-3 mb-3 peserta-item border-start border-3 ps-3 py-2 bg-light bg-opacity-50">
            
            {{-- Nama Peserta (col-md-2) --}}
            <div class="col-md-2">
                <label class="form-label small mb-1">Nama Peserta</label>
                <input type="text" class="form-control form-control-sm" name="peserta_nama[]" placeholder="Nama lengkap" required>
            </div>
            
            {{-- Jabatan (col-md-2) --}}
            <div class="col-md-2">
                <label class="form-label small mb-1">Jabatan</label>
                <input type="text" class="form-control form-control-sm" name="peserta_jabatan[]" placeholder="Contoh: Staf/Guru">
            </div>
            
            {{-- Kontak (col-md-2) --}}
            <div class="col-md-2">
                <label class="form-label small mb-1">No. WA/NIP</label>
                <input type="text" class="form-control form-control-sm" name="peserta_kontak[]" placeholder="08xx atau NIP">
            </div>
            
            {{-- EMAIL (col-md-3) --}}
            <div class="col-md-3">
                <label class="form-label small mb-1">Email</label>
                <input type="email" class="form-control form-control-sm" name="peserta_email[]" placeholder="email@contoh.com">
            </div>
            
            {{-- FILE TTD dan Hapus (col-md-3) --}}
            <div class="col-md-3 ttd-container"> 
                <label class="form-label small mb-1">Tanda Tangan (Wajib)</label>
                
                <div class="signature-box border border-dark rounded p-1 mb-1 bg-white">
                    <canvas class="signature-pad" height="80" style="width: 100%;"></canvas>
                </div>
                
                <button type="button" class="btn btn-sm btn-outline-danger w-100 mb-2 clear-signature-btn">Bersihkan TTD</button>
                
                <input type="hidden" class="ttd-data-input" name="peserta_ttd_data[]" value="">

                <button type="button" class="btn btn-sm btn-danger w-100 remove-peserta-btn">Hapus</button>
            </div>
        </div>
    </template>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const list = document.getElementById('peserta-list');
            const template = document.getElementById('peserta-row-template');
            const addButton = document.getElementById('add-peserta-btn');

            const addPesertaRow = () => {
                const clone = template.content.cloneNode(true);
                list.appendChild(clone);
            };

            // Tambahkan baris pertama secara otomatis
            addPesertaRow();

            addButton.addEventListener('click', addPesertaRow);

            // Listener untuk tombol Hapus (ditempatkan di kontainer utama)
            list.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-peserta-btn')) {
                    // Cari elemen terdekat yang memiliki class 'peserta-item' (yaitu row utamanya)
                    const rowToRemove = e.target.closest('.peserta-item');
                    if (rowToRemove) {
                        rowToRemove.remove();
                    }
                }
            });

            const list = document.getElementById('peserta-list');
            const template = document.getElementById('peserta-row-template');
            const addButton = document.getElementById('add-peserta-btn');
            const form = document.querySelector('form'); // Ambil form utama

            // Fungsi untuk menginisialisasi Signature Pad pada row baru
            const initializeSignaturePad = (row) => {
                const canvas = row.querySelector('.signature-pad');
                const dataInput = row.querySelector('.ttd-data-input');
                const clearButton = row.querySelector('.clear-signature-btn');

                // Pastikan Canvas berukuran penuh
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
                
                const signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)' // Warna latar belakang putih
                });

                // Event listener untuk tombol Hapus TTD
                clearButton.addEventListener('click', (e) => {
                    e.preventDefault(); // Mencegah submit form
                    signaturePad.clear();
                    dataInput.value = ''; // Kosongkan data tersembunyi juga
                });
                
                // Simpan instance SignaturePad ke elemen row (untuk diakses saat submit)
                row.signaturePadInstance = signaturePad; 
            };

            const addPesertaRow = () => {
                const clone = template.content.cloneNode(true);
                const newRow = clone.querySelector('.peserta-item');
                list.appendChild(newRow);
                
                // Inisialisasi Signature Pad pada row yang baru dibuat
                initializeSignaturePad(newRow); 
            };

            // Tambahkan baris pertama secara otomatis dan inisialisasi TTD
            addPesertaRow();

            addButton.addEventListener('click', addPesertaRow);

            // Listener untuk tombol Hapus (seperti sebelumnya)
            list.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-peserta-btn')) {
                    const rowToRemove = e.target.closest('.peserta-item');
                    if (rowToRemove) {
                        rowToRemove.remove();
                    }
                }
            });

            // === LOGIC PENTING: Menangkap Tanda Tangan saat Submit ===
            form.addEventListener('submit', function(e) {
                const pesertaRows = list.querySelectorAll('.peserta-item');
                let allValid = true;

                pesertaRows.forEach((row, index) => {
                    const signaturePad = row.signaturePadInstance;
                    const dataInput = row.querySelector('.ttd-data-input');
                    
                    if (signaturePad && signaturePad.isEmpty()) {
                        // Tanda tangan WAJIB diisi
                        alert(`Tanda tangan peserta ke-${index + 1} harus diisi.`);
                        allValid = false;
                        e.preventDefault();
                        return;
                    }
                    
                    // Konversi tanda tangan (Canvas) ke format Base64 PNG
                    const dataURL = signaturePad.toDataURL('image/png');
                    dataInput.value = dataURL;
                });

                if (!allValid) {
                    e.preventDefault(); // Hentikan submit jika ada yang kosong
                }
                
                // Lanjutkan submit form jika allValid = true
            });
            
        });
    </script>
</x-layout>    
