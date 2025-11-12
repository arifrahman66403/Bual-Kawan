<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan form login admin.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Jika pengguna sudah login, alihkan langsung ke dashboard yang sesuai
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return view('admin.login');
    }

    /**
     * Menangani permintaan otentikasi (login) pengguna.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            // Opsi 'remember' (jika ada di form)
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // 2. Coba otentikasi pengguna. Gunakan 'remember' jika ada di form.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Otentikasi BERHASIL
            $request->session()->regenerate();
            $user = Auth::user(); 
            $role = $user->role;
            
            // 3. Pengalihan (Redirection) berdasarkan role menggunakan if-else if
            if ($role === 'superadmin') {
                // Arahkan Superadmin
                return redirect()->route('superadmin.dashboard')->with('success', "Selamat datang, Superadmin {$user->name}!");
            } elseif ($role === 'admin') {
                // Arahkan Admin
                return redirect()->route('admin.dashboard')->with('success', "Selamat datang, Admin {$user->name}!");
            } elseif ($role === 'operator') {
                // Arahkan Operator
                return redirect()->route('operator.dashboard')->with('success', "Selamat datang, Operator {$user->name}!");
            } else {
                // 4. Role Tidak Diizinkan (Unauthorized Role)
                
                // Lakukan logout paksa untuk user dengan role yang tidak terdaftar di panel admin
                Auth::logout(); 
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kembalikan error menggunakan ValidationException
                throw ValidationException::withMessages([
                    'email' => ['Role Anda tidak memiliki akses ke panel administrasi ini.'],
                ])->onlyInput('email');
            }
        }

        // 5. Otentikasi GAGAL (Email/Password salah)
        // Melempar pengecualian validasi untuk menampilkan pesan error di form login
        throw ValidationException::withMessages([
            'email' => ['Kombinasi Email atau Kata Sandi salah. Silakan coba lagi.'],
        ])->onlyInput('email');
    }

    /**
     * Menangani permintaan logout pengguna.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
    
    /**
     * Fungsi helper untuk mengalihkan ke dashboard berdasarkan role.
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard(string $role)
    {
        if ($role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'operator') {
            return redirect()->route('operator.dashboard');
        }
        
        // Default, alihkan ke login jika rolenya tidak termasuk kategori admin
        return redirect()->route('login');
    }
}
