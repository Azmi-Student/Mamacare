<?php

use Illuminate\Support\Facades\Route;

// --- AUTH CONTROLLERS ---
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DonationController;

// --- MAMA CONTROLLERS ---
use App\Http\Controllers\Mama\DashboardController; // Dashboard Mama
use App\Http\Controllers\Mama\KalenderKehamilanController;
use App\Http\Controllers\Mama\ReservationController; // Reservasi Mama
use App\Http\Controllers\Mama\ChatController;
use App\Http\Controllers\Mama\RekapDataController;
use App\Http\Controllers\Mama\TanyaDokterController;
use App\Http\Controllers\Mama\KickCounterController;
use App\Http\Controllers\Mama\ContractionTimerController;
use App\Http\Controllers\Mama\WeightTrackerController;
use App\Http\Controllers\Mama\BabySizeController;
use App\Http\Controllers\Mama\FoodScannerController;
use App\Http\Controllers\Mama\NutritionGuideController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// --- DOKTER CONTROLLERS ---
// Menggunakan Alias agar tidak bentrok dengan Dashboard Mama
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController; 
use App\Http\Controllers\Doctor\ReservationController as DoctorReservationController;
use App\Http\Controllers\Doctor\JawabPasienController as JawabPasienController;
use App\Http\Controllers\Doctor\KelolaArtikelController as DoctorKelolaArtikelController;
use App\Http\Controllers\Doctor\KelolaNutrisiController as DoctorKelolaNutrisiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- GOOGLE LOGIN ---
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {

    // 1. Role: MAMA
    Route::middleware('role:mama')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Fitur: Kalender Kehamilan
        Route::controller(KalenderKehamilanController::class)->prefix('kalender-kehamilan')->name('mama.kalender')->group(function () {
            Route::get('/', 'index');
            Route::get('/detail', 'detail')->name('.detail');
            Route::get('/reset', 'reset')->name('.reset');
            Route::post('/update-checklist', 'updateChecklist')->name('.update_checklist');
        });

        // Fitur: Reservasi Dokter (Sisi Mama)
        Route::controller(ReservationController::class)->prefix('reservasi-dokter')->name('mama.reservasi')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store')->name('.store');
        });

        // Fitur: Mama AI
        Route::controller(ChatController::class)->prefix('mama-ai')->name('mama.ai')->group(function () {
            Route::get('/', 'index');
            Route::post('/send', 'chat')->name('.send');
        });

        // Fitur: Rekap Data Pemeriksaan
        Route::controller(RekapDataController::class)->prefix('rekap-data')->name('mama.rekap-data')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'detail')->name('.detail');
        });

        // Fitur: Tanya Dokter
        Route::controller(TanyaDokterController::class)->prefix('tanya-dokter')->group(function () {
            Route::get('/', 'index')->name('mama.tanya-dokter');
            Route::get('/chat/{id}', 'chat')->name('mama.tanya-dokter.chat');
            // API Routes (untuk Fetch Data di background)
            Route::get('/messages/{doctorId}', 'getMessages');
            Route::post('/send', 'sendMessage');
        });

        // Fitur: 4 Ekstra
        Route::get('/kick-counter', [KickCounterController::class, 'index'])->name('mama.kick-counter');
        Route::post('/kick-counter', [KickCounterController::class, 'store'])->name('mama.kick-counter.store');
        
        Route::get('/contraction-timer', [ContractionTimerController::class, 'index'])->name('mama.contraction-timer');
        Route::post('/contraction-timer', [ContractionTimerController::class, 'store'])->name('mama.contraction-timer.store');
        
        Route::get('/weight-tracker', [WeightTrackerController::class, 'index'])->name('mama.weight-tracker');
        Route::post('/weight-tracker', [WeightTrackerController::class, 'store'])->name('mama.weight-tracker.store');
        
        Route::get('/baby-size', [BabySizeController::class, 'index'])->name('mama.baby-size');

        Route::get('/food-scanner', [FoodScannerController::class, 'index'])->name('mama.food-scanner');
        Route::post('/food-scanner/scan', [FoodScannerController::class, 'scan'])->name('mama.food-scanner.scan');

        Route::get('/panduan-nutrisi', [NutritionGuideController::class, 'index'])->name('mama.nutrition-guide');
    });


    // 2. Role: ADMIN
Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard (Stats)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Users (CRUD)
    Route::controller(AdminUserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::patch('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
});


    // 3. Role: DOKTER
Route::middleware('role:dokter')->prefix('dokter')->name('dokter.')->group(function () {
    
    // Dashboard Dokter (Logic sudah pakai Controller Baru)
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pasien / Reservasi (Sisi Dokter)
    Route::controller(DoctorReservationController::class)->prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::patch('/{id}', 'update')->name('update');
    });

    // Fitur: Jawab Pasien (Chat Dokter)
    Route::controller(JawabPasienController::class)->prefix('jawab-pasien')->name('chat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/messages/{userId}', 'getMessages')->name('messages');
        Route::post('/send', 'sendMessage')->name('send');
    });

    // Fitur: Manajemen Artikel Dok
    // Panggil pakai nama Alias-nya
    Route::resource('kelola-artikel', DoctorKelolaArtikelController::class);

    // Fitur: Manajemen Nutrisi Dok
    Route::resource('kelola-nutrisi', DoctorKelolaNutrisiController::class);

    // Route tambahan juga pakai nama Alias
    Route::patch('/kelola-artikel/{id}/update-status', [DoctorKelolaArtikelController::class, 'updateStatus'])
        ->name('kelola-artikel.updateStatus');
    
});


    // 4. SHARED
    // Route untuk profile user (semua role)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::delete('/profile/avatar', 'destroyAvatar')->name('profile.avatar.destroy');
    });

    // Route untuk halaman artikel
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])->name('artikel.show'); 

    // 
    Route::post('/donasi/pay', [DonationController::class, 'pay'])->name('donasi.pay');
    Route::get('/donasi/check-status/{orderId}', [DonationController::class, 'checkStatus'])
    ->name('donasi.check');

    // Fitur Komunitas (Shared untuk Mama dan Dokter)
    Route::controller(\App\Http\Controllers\CommunityController::class)->prefix('komunitas')->name('komunitas.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/{id}/like', 'toggleLike')->name('like');
        Route::post('/{id}/comment', 'comment')->name('comment');
    });
});

require __DIR__.'/auth.php';