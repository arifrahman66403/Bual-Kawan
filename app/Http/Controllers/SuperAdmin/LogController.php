<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\KisLog;

class LogController extends Controller
{
    public function index()
    {
        $logs = KisLog::with(['user', 'pengunjung'])->latest()->get();
        return view('superadmin.log.index', compact('logs'));
    }
}