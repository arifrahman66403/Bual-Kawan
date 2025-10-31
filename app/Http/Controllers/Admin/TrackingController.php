<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KisTracking;
use App\Models\KisPengunjung;

class TrackingController extends Controller
{
    // Menampilkan semua tracking
    public function index()
    {
        $trackings = \App\Models\KisTracking::with('pengunjung')->latest()->get();
        return view('admin.index', compact('trackings'));

        $data = KisTracking::with('pengunjung')->get();
        return response()->json($data);
    }

    // Tambahkan catatan tracking baru
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required',
            'catatan' => 'required',
            'status' => 'required|in:disetujui,kunjungan,selesai'
        ]);

        $tracking = KisTracking::create([
            'pengajuan_id' => $request->pengajuan_id,
            'catatan' => $request->catatan,
            'status' => $request->status,
            'created_by' => auth()->id() ?? 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil ditambahkan',
            'data' => $tracking
        ]);
    }

    // Update status tracking
    public function update(Request $request, $id)
    {
        $tracking = KisTracking::findOrFail($id);
        $tracking->update([
            'catatan' => $request->catatan ?? $tracking->catatan,
            'status' => $request->status ?? $tracking->status,
            'edited_by' => auth()->id() ?? 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil diperbarui',
            'data' => $tracking
        ]);
    }

    // Hapus data tracking
    public function destroy($id)
    {
        $tracking = KisTracking::findOrFail($id);
        $tracking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tracking berhasil dihapus'
        ]);
    }
}
