<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // 1. Cari user berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Jika ketemu, langsung login
                Auth::login($user);
                return redirect()->intended('/dashboard');
            } else {
                // 2. Jika tidak ada google_id, cek apakah emailnya sudah terdaftar?
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Jika email sudah ada, update google_id-nya saja, lalu login
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                    ]);
                    Auth::login($existingUser);
                } else {
                    // 3. Jika benar-benar baru, buat akun baru
                    $newUser = User::create([
                        'name'      => $googleUser->name,
                        'email'     => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password'  => Hash::make(Str::random(24)), // Password acak yang aman
                    ]);

                    Auth::login($newUser);
                }

                return redirect()->intended('/dashboard');
            }

        } catch (Exception $e) {
            // Jika ada error (misal: user membatalkan login)
            return redirect('/login')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }
    }
}