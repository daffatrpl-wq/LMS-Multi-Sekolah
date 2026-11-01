<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Jika form pendaftaran Anda tidak mengirimkan role, secara default bisa diatur ke 'siswa' atau sesuai kebutuhan
            'role' => $request->role ?? 'siswa', 
        ]);

        event(new Registered($user));

        // PENTING: Karena ingin diarahkan ke halaman login dulu, 
        // kita TIDAK menjalankan Auth::login($user) di sini.
        // Cukup logout-kan atau pastikan sesi bersih jika terlanjur aktif, 
        // lalu arahkan langsung ke halaman login dengan pesan sukses.

        Auth::logout(); // Memastikan pengguna tidak otomatis masuk

        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan masuk menggunakan akun yang baru saja Anda daftarkan.');
    }
}