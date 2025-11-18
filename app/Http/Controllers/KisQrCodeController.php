<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisPengunjung;
use App\Models\KisPesertaKunjungan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KisQrCodeController extends Controller
{
    /**
     * Menampilkan formulir penambahan peserta rombongan setelah QR dipindai.
     */
    public function showParticipantForm($uid)
    {
        // 1. Cari data Pengajuan berdasarkan UID dan Eager Load qrCode
        $pengunjung = KisPengunjung::where('uid', $uid)->with('qrCode')->first();

        if (!$pengunjung) {
            return view('errors.404')->with('message', 'Kode Pengajuan tidak valid atau tidak ditemukan.');
        }
        
        // Cek apakah data QR Code ada
        if (!$pengunjung->qrCode) {
            return view('kunjungan.status')->with('pengunjung', $pengunjung)
                        ->with('message', 'QR Code belum dibuat untuk pengajuan ini. Silakan hubungi Admin.');
        }

        // 2. CEK KADALUARSA QR CODE (PERBAIKAN UTAMA)
        // Gunakan Carbon::parse()->isPast() untuk mengecek apakah waktu berlaku_sampai sudah lewat
        if (Carbon::parse($pengunjung->qrCode->berlaku_sampai)->isPast()) {
            $expired_date = Carbon::parse($pengunjung->qrCode->berlaku_sampai)->format('d F Y H:i');
            $message = "❌ Link pengisian data peserta (QR Code) telah *kadaluarsa* pada {$expired_date}. Silakan hubungi Admin untuk perpanjangan masa berlaku.";
            
            // Arahkan ke halaman status/error dengan pesan kadaluarsa
            return view('kunjungan.status')->with('pengunjung', $pengunjung)
                                            ->with('message', $message)
                                            ->with('status', 'expired'); // Anda bisa menggunakan 'status' untuk styling
        }

        // 3. Cek status (opsional tapi disarankan)
        if (!in_array($pengunjung->status, ['disetujui', 'kunjungan'])) {
             return view('kunjungan.status')->with('pengunjung', $pengunjung)
                        ->with('message', 'Pengajuan ini belum disetujui atau sudah selesai.')
                        ->with('status', $pengunjung->status);
        }

        // 4. Jika semua valid, tampilkan formulir
        return view('kunjungan.tambah_peserta', compact('pengunjung'));
    }

    /**
     * Menyimpan data peserta rombongan dari formulir yang diakses melalui QR.
     */
    public function storeParticipantData(Request $request, $uid)
    {
        // ... (Kode storeParticipantData yang sama seperti sebelumnya) ...
        
        // PERHATIAN: Idealnya, cek kadaluarsa diulang di sini sebelum penyimpanan, 
        // untuk mencegah input dari tab browser yang dibuka lama.
        $pengunjung = KisPengunjung::where('uid', $uid)->with('qrCode')->first();

        // Cek Kadaluarsa lagi sebelum menyimpan (Preventif)
        if (!$pengunjung || !$pengunjung->qrCode || Carbon::parse($pengunjung->qrCode->berlaku_sampai)->isPast()) {
            return redirect()->route('pengunjung.scan', $uid)->with('error', 'Gagal menyimpan: Sesi pengisian data telah kadaluarsa. Silakan refresh halaman atau hubungi Admin.');
        }

        // Validasi input data lainnya (tanpa validasi file ttd)
        $request->validate([
            'peserta_nama.*' => 'required|string|max:255', 
            'peserta_jabatan.*' => 'nullable|string|max:255',
            'peserta_kontak.*' => 'nullable|string|max:20',
            'peserta_email.*' => 'nullable|email|max:255',
            // Validasi untuk Base64: memastikan string ada dan tidak kosong
            'peserta_ttd_data.*' => 'required|string', 
        ]);

        DB::beginTransaction();
        
        try {
            // AMBIL DATA BASE64 DARI INPUT TERSEMBUNYI
            $ttd_data_array = $request->peserta_ttd_data;
            
            $peserta_data_massal = [];
            
            $nama_array = $request->peserta_nama;
            $jabatan_array = $request->peserta_jabatan;
            $kontak_array = $request->peserta_kontak;
            $email_array = $request->peserta_email;
            
            $count = count($nama_array);

            for ($i = 0; $i < $count; $i++) {
                $nama = trim($nama_array[$i] ?? '');
                
                if (!empty($nama)) {
                    $ttd_path = null;
                    
                    // === LOGIKA BARU UNTUK BASE64 ===
                    $base64Image = $ttd_data_array[$i] ?? null;
                    
                    // Cek apakah data Base64 ada dan valid (misal: 'data:image/png;base64,...')
                    if ($base64Image) {
                        // Hapus prefix "data:image/png;base64,"
                        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
                        $base64Image = str_replace(' ', '+', $base64Image); // Perbaiki spasi

                        // Decode data Base64 menjadi binary data
                        $imageData = base64_decode($base64Image);
                        
                        if ($imageData === false) {
                            throw new \Exception("Gagal mengkonversi tanda tangan peserta ke-" . ($i + 1));
                        }
                        
                        $fileName = 'ttd_' . $pengunjung->uid . '_' . ($i + 1) . '_' . time() . '.png';
                        
                        // Simpan file ke storage
                        Storage::disk('public')->put('ttd_peserta/' . $fileName, $imageData);
                        $ttd_path = 'ttd_peserta/' . $fileName; // Path yang akan disimpan ke DB
                    }
                    
                    $peserta_data_massal[] = [
                        'uid' => Str::uuid(),
                        'pengunjung_id' => $pengunjung->uid, 
                        'nama' => $nama,
                        'nip' => $kontak_array[$i] ?? null, 
                        'jabatan' => $jabatan_array[$i] ?? null,
                        'email' => $email_array[$i] ?? null, 
                        'wa' => $kontak_array[$i] ?? null, 
                        'file_ttd' => $ttd_path, // <-- Path file TTD dari Base64
                        'created_by' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($peserta_data_massal)) {
                KisPesertaKunjungan::insert($peserta_data_massal);
            }

            DB::commit(); 
            return view('kunjungan.konfirmasi')->with('pengunjung', $pengunjung)
                                            ->with('success', 'Data rombongan berhasil ditambahkan. Terima kasih!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}