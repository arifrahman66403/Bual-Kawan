<?php

namespace App\Http\Controllers;

use App\Models\KisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KisUserController extends Controller
{
    // Menampilkan Daftar User
    public function index(Request $request)
    {
        // Search logic simple
        $query = KisUser::latest();
        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('user', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Halaman Tambah
    public function create()
    {
        return view('admin.users.create');
    }

    // Simpan Data Baru
    public function store(Request $request)
    {
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
            'user'      => $request->user, // Username
            'wa'        => $request->wa,
            'role'      => $request->role,
            'pass'      => Hash::make($request->pass), // Hash password
            'is_active' => 1 // Default aktif
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Administrator baru berhasil ditambahkan.');
    }

    // Halaman Edit
    public function edit($id)
    {
        $user = KisUser::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $user = KisUser::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('kis_user')->ignore($user->id)],
            'user'  => ['required', 'string', Rule::unique('kis_user')->ignore($user->id)],
            'role'  => 'required|in:superadmin,admin,operator',
            'wa'    => 'nullable|numeric',
            'pass'  => 'nullable|min:6', // Password nullable saat edit
        ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
            'user'  => $request->user,
            'wa'    => $request->wa,
            'role'  => $request->role,
        ];

        // Jika password diisi, update password baru
        if ($request->filled('pass')) {
            $data['pass'] = Hash::make($request->pass);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data administrator diperbarui.');
    }

    // Fitur Toggle Status (Aktif/Non-Aktif)
    public function toggleStatus($id)
    {
        $user = KisUser::findOrFail($id);
        
        // Switch status: Jika 1 jadi 0, jika 0 jadi 1
        $user->is_active = $user->is_active == 1 ? 0 : 1;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->nama} berhasil {$statusText}.");
    }

    // Hapus User (Soft Delete)
    public function destroy($id)
    {
        $user = KisUser::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Administrator berhasil dihapus.');
    }
}