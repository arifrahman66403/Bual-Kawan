<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba otentikasi menggunakan guard default (web)
        if (Auth::attempt($credentials)) {
            
            // Otentikasi BERHASIL.
            $user = Auth::user(); 
            $role = $user->role;
            
            // 3. Regenerasi sesi
            $request->session()->regenerate();
            
            // 4. Pengalihan (Redirection) berdasarkan role
            switch ($role) {
                case 'superadmin':
                    // Arahkan Superadmin ke halaman Superadmin Dashboard
                    return redirect()->route('superadmin.dashboard'); 
                    break;
                
                case 'admin':
                    // Arahkan Admin ke halaman Admin Dashboard
                    return redirect()->route('admin.dashboard'); 
                    break;
                
                case 'operator':
                    // Arahkan Operator ke halaman Operator Dashboard
                    return redirect()->route('operator.dashboard'); 
                    break;
                
                default:
                    // Jika role tidak termasuk 'superadmin', 'admin', atau 'operator'
                    // Lakukan logout paksa
                    Auth::logout(); 
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withErrors([
                        'email' => 'Role Anda tidak memiliki akses ke halaman administrasi ini.',
                    ])->onlyInput('email');
                    break;
            }
        }

        // 5. Otentikasi GAGAL (Email/Password salah)
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login admin
        return redirect('/admin/login');
    }
}