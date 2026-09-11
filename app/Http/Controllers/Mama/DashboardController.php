<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil jadwal reservasi (tanggal saja) yang statusnya masih aktif
        $appointments = Appointment::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('date')
            ->toArray();

        return view('mama.dashboard.index', compact('appointments'));
    }
}
