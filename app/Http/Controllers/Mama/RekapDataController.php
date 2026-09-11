<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Appointment;

class RekapDataController extends Controller
{
    public function index()
{
    $rekaps = Appointment::where('user_id', auth()->id())
        ->where('status', 'completed')
        ->with('doctor.user')
        ->orderBy('date', 'desc')
        ->paginate(12);

    return view('mama.rekap-data.index', compact('rekaps'));
}

public function detail($id) // Nama method kita ganti detail agar sinkron
{
    $rekap = Appointment::where('user_id', auth()->id())
        ->with('doctor')
        ->findOrFail($id);

    return view('mama.rekap-data.detail', compact('rekap'));
}
}
