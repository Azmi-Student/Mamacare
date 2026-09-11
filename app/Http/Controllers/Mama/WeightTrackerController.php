<?php

namespace App\Http\Controllers\Mama;

use App\Http\Controllers\Controller;
use App\Models\WeightLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeightTrackerController extends Controller
{
    public function index()
    {
        $logs = WeightLog::where('user_id', auth()->id())
                              ->orderBy('week_number', 'asc')
                              ->get();
        
        $labels = $logs->pluck('week_number')->map(function($week) { return 'Mg ' . $week; })->toArray();
        $data = $logs->pluck('weight')->toArray();

        return view('mama.weight-tracker.index', compact('logs', 'labels', 'data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'week_number' => 'required|integer',
        ]);

        WeightLog::create([
            'user_id' => auth()->id(),
            'weight' => $request->weight,
            'week_number' => $request->week_number,
            'date' => Carbon::now()->format('Y-m-d'),
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Berat badan berhasil dicatat!');
    }
}
