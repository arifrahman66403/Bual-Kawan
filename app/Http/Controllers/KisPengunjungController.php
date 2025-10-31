<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KisPengunjungController extends Controller
{
    public function index()
    {
        $pengunjungs = KisPengunjung::orderBy('created_at', 'desc')->get();
        return view('pengunjung.index', compact('pengunjungs'));
    }

    public function create()
    {
        return view('pengunjung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tgl_kunjungan' => 'required|date',
            'nama_perwakilan' => 'required|string|max:255',
            'email_perwakilan' => 'required|email',
            'wa_perwakilan' => 'required|string|max:20',
        ]);

        $pengunjung = KisPengunjung::create([
            'uid' => Str::uuid(),
            'kode_daerah' => 'K-' . rand(1000, 9999),
            'nama_instansi' => $request->nama_instansi,
            'satuan_kerja' => $request->satuan_kerja,
            'tujuan' => $request->tujuan,
            'tgl_kunjungan' => $request->tgl_kunjungan,
            'nama_perwakilan' => $request->nama_perwakilan,
            'email_perwakilan' => $request->email_perwakilan,
            'wa_perwakilan' => $request->wa_perwakilan,
            'status' => 'pengajuan',
            'created_by' => Auth::user()->id,
        ]);

        // ✅ HARUSNYA KE kis_tracking
        KisTracking::create([
            'pengajuan_id' => $pengunjung->uid,
            'status' => 'disetujui', // atau 'pengajuan' tergantung alur kamu
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('pengunjung.create')
            ->with('success', 'Data pengunjung berhasil diajukan!');
    }

    public function show($id)
    {
        $pengunjung = KisPengunjung::findOrFail($id);
        return view('pengunjung.show', compact('pengunjung'));
    }

    public function verifyList()
    {
        $pengunjungs = KisPengunjung::orderBy('created_at', 'desc')->get();
        return view('admin.verify', compact('pengunjungs'));
    }
}
