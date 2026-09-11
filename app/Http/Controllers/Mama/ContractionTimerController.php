<?php

namespace App\Http\Controllers\Mama;

use App\Http\Controllers\Controller;
use App\Models\ContractionTimer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractionTimerController extends Controller
{
    public function index()
    {
        $history = ContractionTimer::where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->get();
        return view('mama.contraction-timer.index', compact('history'));
    }

    public function store(Request $request)
    {
        ContractionTimer::create([
            'user_id' => auth()->id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_seconds' => $request->duration_seconds,
            'interval_seconds' => $request->interval_seconds ?: null,
            'intensity' => $request->intensity ?? 'Ringan',
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Kontraksi berhasil dicatat!');
    }
}
