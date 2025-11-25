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

        // 1. Validasi input + captcha
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // 2. Coba otentikasi pengguna
        if (Auth::attempt($credentials)) {
            
            // Otentikasi BERHASIL
            $request->session()->regenerate();
            $user = Auth::user(); 
            $role = $user->role;
            
            // 3. Pengalihan berdasarkan role
            if ($role === 'superadmin' || $role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', "Selamat datang, {$user->name}!");
            } elseif ($role === 'operator') {
                return redirect()->route('beranda')->with('success', "Selamat datang, {$user->name}!");
            } else {
                // Role tidak diizinkan
                Auth::logout(); 
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Role Anda tidak memiliki akses ke panel administrasi ini.']);
            }
        }

        // 5. Otentikasi GAGAL (Email/Password salah)
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Kombinasi Email atau Kata Sandi salah. Silakan coba lagi.']);
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
            return redirect()->route('beranda');
        }
        
        // Default, alihkan ke login jika rolenya tidak termasuk kategori admin
        return redirect()->route('login');
    }
}
