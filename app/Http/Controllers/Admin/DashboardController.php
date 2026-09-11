<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Statistik (Chart/Card)
        $totalUsers  = User::count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalMama   = User::where('role', 'mama')->count();
        $totalAdmin  = User::where('role', 'admin')->count();

        // 2. Ambil Daftar User (Tabel) - INI YANG TADI KURANG
        // Kita ambil data user terbaru, dipaginate 10 per halaman
        $users = User::latest()->paginate(10);

        // 3. Kirim semua data ke view
        return view('admin.index', compact('totalUsers', 'totalDokter', 'totalMama', 'totalAdmin', 'users'));
    }
}