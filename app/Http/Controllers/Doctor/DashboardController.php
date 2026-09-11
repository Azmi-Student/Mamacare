<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA DOKTER
        // Cari data di tabel 'doctors' milik user yang sedang login.
        // Kita tidak perlu with('user') untuk nama, karena di tabel doctors sudah ada namanya sendiri.
        $doctor = Doctor::where('user_id', Auth::id())->first();

        // Cek validasi kalau datanya belum di-seed/belum ada
        if (!$doctor) {
            // Fallback sederhana jika data dokter belum ada, kita pakai data user biasa sementara
            $doctor = Auth::user();
            // Buat properti dummy biar view gak error
            $doctor->specialist = '-'; 
            $doctor->avatar = substr($doctor->name, 0, 1);
        } else {
            // 2. MANIPULASI AVATAR (LOGIC DR. BOYKE)
            // Data $doctor->name sekarang isinya: "Dr. Boyke Dian, Sp.OG" (Sesuai Seeder)
            
            // a. Hapus "Dr." atau "dr."
            $cleanName = str_replace(['Dr. ', 'dr. '], '', $doctor->name); // Hasil: "Boyke Dian, Sp.OG"
            
            // b. Ambil huruf pertama
            $doctor->avatar = strtoupper(substr($cleanName, 0, 1)); // Hasil: "B"
        }

        // 3. HITUNG STATISTIK 
        // Karena $doctor di sini adalah instance model Doctor, kita bisa langsung akses relasi appointments
        $totalPasien = $doctor instanceof Doctor ? $doctor->appointments()->count() : 0;
        $pasienHariIni = $doctor instanceof Doctor ? $doctor->appointments()->whereDate('date', now())->count() : 0;

        // 4. AMBIL DATA PASIEN
        $pasiens = User::where('role', 'mama')->latest()->paginate(5);

        return view('dokter.index', compact('doctor', 'totalPasien', 'pasienHariIni', 'pasiens'));
    }
}