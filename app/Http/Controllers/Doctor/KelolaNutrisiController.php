<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\NutritionGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelolaNutrisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guides = NutritionGuide::where('doctor_id', Auth::id())->latest()->paginate(10);
        return view('dokter.kelola-nutrisi.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.kelola-nutrisi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Rekomendasi,Pantangan',
            'trimester' => 'required|in:1,2,3,Umum',
            'description' => 'required|string',
        ]);

        NutritionGuide::create([
            'doctor_id' => Auth::id(),
            'title' => $request->title,
            'type' => $request->type,
            'trimester' => $request->trimester,
            'description' => $request->description,
        ]);

        return redirect()->route('dokter.kelola-nutrisi.index')->with('success', 'Panduan Nutrisi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $guide = NutritionGuide::where('doctor_id', Auth::id())->findOrFail($id);
        return view('dokter.kelola-nutrisi.edit', compact('guide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Rekomendasi,Pantangan',
            'trimester' => 'required|in:1,2,3,Umum',
            'description' => 'required|string',
        ]);

        $guide = NutritionGuide::where('doctor_id', Auth::id())->findOrFail($id);
        $guide->update([
            'title' => $request->title,
            'type' => $request->type,
            'trimester' => $request->trimester,
            'description' => $request->description,
        ]);

        return redirect()->route('dokter.kelola-nutrisi.index')->with('success', 'Panduan Nutrisi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guide = NutritionGuide::where('doctor_id', Auth::id())->findOrFail($id);
        $guide->delete();

        return redirect()->route('dokter.kelola-nutrisi.index')->with('success', 'Panduan Nutrisi berhasil dihapus!');
    }
}
