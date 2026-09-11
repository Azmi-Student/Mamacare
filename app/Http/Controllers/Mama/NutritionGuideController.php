<?php

namespace App\Http\Controllers\Mama;

use App\Http\Controllers\Controller;
use App\Models\NutritionGuide;
use Illuminate\Http\Request;

class NutritionGuideController extends Controller
{
    public function index()
    {
        $guides = NutritionGuide::with('doctor')->get();
        return view('mama.nutrition-guide.index', compact('guides'));
    }
}
