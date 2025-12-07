<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\NutrisiController;
use App\Http\Controllers\KehamilanController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DokterController;
use App\Models\User;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DokterChatController; // <--- Jangan lupa import ini di paling atas
use App\Http\Controllers\MamaAiController;

Route::post('/chat-ai', [MamaAiController::class, 'chat'])->name('chat.ai');
Route::middleware(['auth'])->group(function () {
    
    // Halaman Tanya Dokter
    Route::get('/tanya-dokter', function () {
        $dokters = \App\Models\User::where('role', 'dokter')->get();
        return view('fitur.tanya-dokter', compact('dokters'));
    });

    // API Kirim Pesan
    Route::post('/chat/store/{dokterId}', [ChatController::class, 'store'])->name('chat.store');
    
    // API Ambil Pesan (Route Baru)
    Route::get('/chat/get/{dokterId}', [ChatController::class, 'getMessages'])->name('chat.get');
});

// Token transaksi Snap
Route::middleware('auth')->post('/donasi/token', [DonasiController::class, 'getToken']);

// Webhook notifikasi dari Midtrans
Route::post('/midtrans/callback', [DonasiController::class, 'handleCallback']);

// Landing Page
Route::get('/landing', function () {
    return view('landingpage'); // file: resources/views/landingpage.blade.php
})->name('landingpage');

// Main Page (default root, hanya setelah login)
Route::get('/', function () {
    return view('main');
})->middleware('auth')->name('home');


// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->put('/profile', [UserController::class, 'update'])->name('profile.update');

// Social Authentication Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Features Routes
Route::middleware('auth')->group(function () {
    Route::get('/kalender-kehamilan', function () {
        return view('fitur.kalender-kehamilan');
    });
    Route::middleware('auth')->get('/pengaturan', function () {
    return view('fitur.pengaturan');
})->name('fitur.pengaturan');

Route::get('/mama-ai', function () {
        return view('fitur.mama-ai');
    })->name('mama.ai');
    Route::middleware('auth')->get('/rekap-data', [ReservasiController::class, 'rekapDataCheckup']);

    Route::get('/reservasi-dokter', function () {
        $dokters = User::where('role', 'dokter')->get(); // Fetch doctors
        return view('fitur.reservasi-dokter', compact('dokters'));
    });

    Route::post('/reservasi', [ReservasiController::class, 'store']);
    Route::get('/reservasi-data', [ReservasiController::class, 'data']);
});

// Nutrisi API
Route::get('/api/nutrisi', [NutrisiController::class, 'getNutrisi']);

// Kehamilan Routes
Route::middleware('auth')->group(function () {
    Route::get('/kehamilan', [KehamilanController::class, 'ambil']);
    Route::post('/kehamilan', [KehamilanController::class, 'simpan']);
});

// Admin Routes (with admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('admin.dashboard');

    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

// Dokter Routes (with dokter middleware)
Route::middleware(['auth', 'dokter'])->prefix('dokter')->group(function () {
    
    // --- DASHBOARD & RESERVASI ---
    Route::get('/', [DokterController::class, 'index'])->name('dokter.dashboard');
    Route::get('/daftar-reservasi', [DokterController::class, 'daftarReservasi'])->name('dokter.daftar-reservasi');
    Route::post('/reservasi/{id}/status', [DokterController::class, 'updateStatus'])->name('dokter.updateStatus');
    Route::post('/reservasi/{id}/hasil-checkup', [DokterController::class, 'updateHasilCheckup'])->name('dokter.updateHasilCheckup');
    Route::get('/reservasi/{id}/get-hasil-checkup', [DokterController::class, 'getHasilCheckup'])->name('dokter.getHasilCheckup');
    Route::delete('/dokter/reservasi/{id}', [DokterController::class, 'destroy'])->name('dokter.reservasi.destroy');

    // --- PENGATURAN ---
    Route::get('/pengaturan', function () {
        return view('dokter.pengaturan'); 
    })->name('dokter.pengaturan');

    Route::put('/profile', [DokterController::class, 'update'])->name('dokter.profile.update');


    // ===================================================
    //  FITUR CHAT DOKTER (Tambahan Baru)
    // ===================================================
    
    // 1. Halaman Utama Chat (View)
    Route::get('/chat', [DokterChatController::class, 'index'])->name('dokter.chat');

    // 2. API: Ambil Riwayat Pesan (Load Chat)
    Route::get('/chat/get/{pasienId}', [DokterChatController::class, 'getMessages'])->name('dokter.chat.get');

    // 3. API: Kirim Pesan Balasan (Reply)
    Route::post('/chat/store/{pasienId}', [DokterChatController::class, 'store'])->name('dokter.chat.store');

});