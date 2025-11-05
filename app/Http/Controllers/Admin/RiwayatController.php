<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\KisQrCode;
use App\Models\KisPengunjung;
use App\Models\KisTracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // Menampilkan semua tracking
    public function index()
    {
        $trackings = \App\Models\KisTracking::with('pengunjung')->latest()->get();
        return view('admin.riwayat', compact('trackings'));

        $data = KisTracking::with('pengunjung')->get();
        return response()->json($data);

    }
}