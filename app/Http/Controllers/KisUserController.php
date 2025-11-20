<?php

namespace App\Http\Controllers;

use App\Models\KisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // PASTI ADA!
// Pastikan Controller ini extend BaseController yang punya AuthorizesRequests trait
// Atau import trait manual jika perlu: use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KisUserController extends Controller
{
    use AuthorizesRequests; // Uncomment jika error method authorize not found

    // Menampilkan Daftar User
    public function index(Request $request)
    {
        // POLICY: Cek apakah user boleh melihat daftar user
        $this->authorize('viewAny', KisUser::class);

        // Search logic simple
        $query = KisUser::latest();
        
        // Filter tambahan: Jika yang login 'operator', dia cuma bisa lihat dirinya sendiri
        // (Meskipun di Policy viewAny sudah dicek, filter ini untuk tampilan tabel)
        if (auth()->user()->role === 'operator') {
            $query->where('id', auth()->id());
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('user', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Halaman Tambah
    public function create()
    {
        // POLICY: Cek apakah user boleh membuat user baru
        $this->authorize('create', KisUser::class);

        return view('admin.users.create');
    }

    // Simpan Data Baru
    public function store(Request $request)
    {
        // 1. POLICY CHECK
        $this->authorize('create', KisUser::class);

        // 2. PROTEKSI TAMBAHAN: 
        // Admin tidak boleh membuat user dengan role 'superadmin' atau 'admin'
        if (auth()->user()->role === 'admin' && $request->role !== 'operator') {
            abort(403, 'Admin hanya diizinkan membuat akun Operator.');
        }

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:kis_user,email',
            'user'  => 'required|string|unique:kis_user,user',
            'role'  => 'required|in:superadmin,admin,operator',
            'pass'  => 'required|min:6',
            'wa'    => 'nullable|numeric',
        ]);

        KisUser::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'user'      => $request->user,
            'wa'        => $request->wa,
            'role'      => $request->role,
            'pass'      => Hash::make($request->pass),
            'is_active' => 1
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Administrator baru berhasil ditambahkan.');
    }

    // Halaman Edit
    public function edit($id)
    {
        $user = KisUser::findOrFail($id);

        // POLICY: Cek apakah user boleh mengedit target user ini
        // (Admin tidak akan bisa akses ini jika targetnya Admin lain/Superadmin)
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $user = KisUser::findOrFail($id);

        // 1. POLICY CHECK
        $this->authorize('update', $user);

        // 2. PROTEKSI TAMBAHAN:
        // Jika yang login Admin, dia tidak boleh menaikkan role siapapun menjadi Superadmin
        if (auth()->user()->role === 'admin' && $request->role === 'superadmin') {
            abort(403, 'Admin tidak diizinkan menetapkan role Superadmin.');
        }
        
        // Jika Admin mengedit diri sendiri, jangan biarkan dia mengubah rolenya jadi Operator (takut terkunci)
        // Kecuali memang disengaja, tapi biasanya admin tetap admin.

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('kis_user')->ignore($user->id)],
            'user'  => ['required', 'string', Rule::unique('kis_user')->ignore($user->id)],
            'role'  => 'required|in:superadmin,admin,operator',
            'wa'    => 'nullable|numeric',
            'pass'  => 'nullable|min:6',
        ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
            'user'  => $request->user,
            'wa'    => $request->wa,
            'role'  => $request->role,
        ];

        if ($request->filled('pass')) {
            $data['pass'] = Hash::make($request->pass);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data administrator diperbarui.');
    }

    // Fitur Toggle Status
    public function toggleStatus($id)
    {
        $user = KisUser::findOrFail($id);
        
        // POLICY: Mengubah status dianggap sama dengan update
        // Admin tidak bisa mematikan akun Admin lain
        $this->authorize('update', $user);

        // Proteksi: Jangan biarkan user menonaktifkan dirinya sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }
        
        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->nama} berhasil {$statusText}.");
    }

    // Hapus User
    public function destroy($id)
    {
        $user = KisUser::findOrFail($id);

        // POLICY: Cek hak hapus (Admin hanya bisa hapus Operator)
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Administrator berhasil dihapus.');
    }
}