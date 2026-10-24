<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- FITUR LOGIN ---
    public function showLogin()
    {
        if (Auth::check()) return $this->redirectUser(Auth::user()->role);
        return view('auth.login');
    }

    public function login(Request $request)
    {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();

        // Pengecekan role setelah berhasil login
        if (auth()->user()->role === 'admin') {
            return redirect()->route('dashboard'); // Admin diarahkan ke dashboard
        } elseif (auth()->user()->role === 'staff') {
            // UBAH BAGIAN INI: Arahkan staff ke dashboard (sebelumnya mungkin ke menu pesanan)
            return redirect()->route('dashboard'); 
        }

        return redirect('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
    }

    // --- FITUR REGISTER ---
    public function showRegister()
    {
        if (Auth::check()) return $this->redirectUser(Auth::user()->role);
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', 
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di Seblak Ronyok.');
    }

    // --- FITUR LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // --- FUNGSI CERDAS REDIRECT ---
    private function redirectUser($role)
    {
        return redirect()->route('dashboard');
    }
}