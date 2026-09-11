<?php

namespace App\Http\Controllers\Mama;

use App\Http\Controllers\Controller;
use App\Models\KickCounter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KickCounterController extends Controller
{
    public function index()
    {
        $history = KickCounter::where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->get();
        return view('mama.kick-counter.index', compact('history'));
    }

    public function store(Request $request)
    {
        KickCounter::create([
            'user_id' => auth()->id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes ?: null,
            'kicks_count' => $request->kicks_count,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Sesi tendangan berhasil disimpan!');
    }
}
