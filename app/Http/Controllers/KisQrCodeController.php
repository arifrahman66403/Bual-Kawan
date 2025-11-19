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
        $qr = $pengunjung->qrCode;
        $now = Carbon::now();

        // Jika berlaku_sampai ada, cek isPast; jika tidak ada, anggap tidak kadaluarsa
        if (!empty($qr->berlaku_sampai) && Carbon::parse($qr->berlaku_sampai)->isPast()) {
            $expired_date = Carbon::parse($qr->berlaku_sampai)->format('d F Y H:i');
            $message = "❌ Link pengisian data peserta (QR Code) telah kadaluarsa pada {$expired_date}. Silakan hubungi Admin untuk perpanjangan masa berlaku.";
            
            return view('kunjungan.status')->with('pengunjung', $pengunjung)
                                           ->with('message', $message)
                                           ->with('status', 'expired');
        }

        // Jika berlaku_mulai ada, cek belum berlaku
        if (!empty($qr->berlaku_mulai) && Carbon::parse($qr->berlaku_mulai)->isFuture()) {
            $start_date = Carbon::parse($qr->berlaku_mulai)->format('d F Y H:i');
            $message = "❌ QR Code belum aktif. Berlaku mulai {$start_date}.";
            return view('kunjungan.status')->with('pengunjung', $pengunjung)
                                           ->with('message', $message)
                                           ->with('status', 'not_active');
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
        // Prevent double-submit from very old tabs: cek pengunjung & QR lagi
        $pengunjung = KisPengunjung::where('uid', $uid)->with('qrCode')->first();

        if (!$pengunjung || !$pengunjung->qrCode) {
            return redirect()->route('pengunjung.scan', $uid)->with('error', 'Sesi tidak valid. Silakan refresh halaman atau hubungi Admin.');
        }

        $qr = $pengunjung->qrCode;
        $now = Carbon::now();

        if (!empty($qr->berlaku_sampai) && Carbon::parse($qr->berlaku_sampai)->isPast()) {
            return redirect()->route('pengunjung.scan', $uid)->with('error', 'Gagal menyimpan: Sesi pengisian data telah kadaluarsa.');
        }

        if (!empty($qr->berlaku_mulai) && Carbon::parse($qr->berlaku_mulai)->isFuture()) {
            return redirect()->route('pengunjung.scan', $uid)->with('error', 'Gagal menyimpan: QR Code belum aktif.');
        }

        $request->validate([
            // Diubah menjadi nullable agar baris kosong (jika dihapus di FE) tidak menyebabkan error required
            'peserta_nama.*' => 'nullable|string|max:255', 
            'peserta_jabatan.*' => 'nullable|string|max:255',
            'peserta_kontak.*' => 'nullable|string|max:20',
            'peserta_email.*' => 'nullable|email|max:255',
            // Ini tetap required karena TTD harus ada jika Nama Peserta diisi (validasi JS)
            'peserta_ttd_data.*' => 'nullable|string', 
        ]);

        DB::beginTransaction();
        
        try {
            $ttd_data_array = $request->peserta_ttd_data;
            $peserta_data_massal = [];
            
            $nama_array = $request->peserta_nama;
            $jabatan_array = $request->peserta_jabatan;
            $kontak_array = $request->peserta_kontak;
            $email_array = $request->peserta_email;
            
            $count = count($nama_array);
            $total_peserta = 0; // Tambahkan penghitung peserta

            for ($i = 0; $i < $count; $i++) {
                $nama = trim($nama_array[$i] ?? '');
                
                if (!empty($nama)) {
                    $total_peserta++; // Hitung peserta yang valid
                    $ttd_path = null;
                    
                    // ... (LOGIKA BASE64 TTD, TIDAK BERUBAH) ...
                    $base64Image = $ttd_data_array[$i] ?? null;
                    
                    if ($base64Image) {
                        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
                        $base64Image = str_replace(' ', '+', $base64Image); 

                        $imageData = base64_decode($base64Image);
                        
                        if ($imageData === false) {
                            throw new \Exception("Gagal mengkonversi tanda tangan peserta ke-" . ($i + 1));
                        }
                        
                        $fileName = 'ttd_' . $pengunjung->uid . '_' . ($i + 1) . '_' . time() . '.png';
                        Storage::disk('public')->put('ttd_peserta/' . $fileName, $imageData);
                        $ttd_path = 'ttd_peserta/' . $fileName; 
                    }
                    
                    $peserta_data_massal[] = [
                        'uid' => Str::uuid(),
                        'pengunjung_id' => $pengunjung->uid, 
                        'nama' => $nama,
                        'nip' => $kontak_array[$i] ?? null, 
                        'jabatan' => $jabatan_array[$i] ?? null,
                        'email' => $email_array[$i] ?? null, 
                        'wa' => $kontak_array[$i] ?? null, 
                        'file_ttd' => $ttd_path, 
                        'created_by' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // === LANGKAH 1: HAPUS DATA LAMA (PENTING) ===
            // Agar tidak terjadi duplikasi jika pengunjung submit ulang
            KisPesertaKunjungan::where('pengunjung_id', $pengunjung->uid)->delete();


            if (!empty($peserta_data_massal)) {
                KisPesertaKunjungan::insert($peserta_data_massal);
            }
            
            // === LANGKAH 2: UPDATE STATUS KisPengunjung ===
            $pengunjung->update([
                'status' => 'kunjungan', // <-- Mengubah status pengajuan utama!
                'jumlah_peserta_diinput' => $total_peserta, // Opsional: Simpan jumlah peserta yang berhasil diinput
            ]);
            
            DB::commit(); 
            return view('kunjungan.konfirmasi')->with('pengunjung', $pengunjung)
                                               ->with('success', 'Data rombongan berhasil ditambahkan. Status kunjungan Anda telah **Aktif**!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}