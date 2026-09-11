<?php

namespace App\Http\Controllers\Mama;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BabySizeController extends Controller
{
    public function index()
    {
        $kehamilan = auth()->user()->kehamilan;
        $week = 0;
        
        if ($kehamilan && $kehamilan->hpht) {
            $hpht = Carbon::parse($kehamilan->hpht);
            $week = (int) floor($hpht->diffInDays(Carbon::now()) / 7);
        }

        $babySize = $this->getBabySizeData($week);

        return view('mama.baby-size.index', compact('week', 'babySize'));
    }

    private function getBabySizeData($week)
    {
        if ($week < 4) return ['fruit' => 'Biji Poppy', 'length' => '0.1 cm', 'weight' => '< 1 g', 'emoji' => '🌱', 'desc' => 'Janin masih sangat kecil, sebesar biji poppy.'];
        if ($week < 8) return ['fruit' => 'Blueberry', 'length' => '1.5 cm', 'weight' => '1 g', 'emoji' => '🫐', 'desc' => 'Jantungnya sudah mulai berdetak!'];
        if ($week < 12) return ['fruit' => 'Jeruk Nipis', 'length' => '5.4 cm', 'weight' => '14 g', 'emoji' => '🍋', 'desc' => 'Organ utama sudah terbentuk lengkap.'];
        if ($week < 16) return ['fruit' => 'Alpukat', 'length' => '11.6 cm', 'weight' => '100 g', 'emoji' => '🥑', 'desc' => 'Bunda mungkin mulai merasakan kepakan halus.'];
        if ($week < 20) return ['fruit' => 'Pisang', 'length' => '25.6 cm', 'weight' => '300 g', 'emoji' => '🍌', 'desc' => 'Tendangannya mulai terasa kuat.'];
        if ($week < 24) return ['fruit' => 'Jagung', 'length' => '30 cm', 'weight' => '600 g', 'emoji' => '🌽', 'desc' => 'Janin bisa mendengar suara Bunda lho!'];
        if ($week < 28) return ['fruit' => 'Terong', 'length' => '37 cm', 'weight' => '1 kg', 'emoji' => '🍆', 'desc' => 'Mata janin sudah bisa membuka dan berkedip.'];
        if ($week < 32) return ['fruit' => 'Kelapa', 'length' => '42 cm', 'weight' => '1.7 kg', 'emoji' => '🥥', 'desc' => 'Ruang gerak makin sempit, tendangan makin terasa.'];
        if ($week < 36) return ['fruit' => 'Melon', 'length' => '47 cm', 'weight' => '2.6 kg', 'emoji' => '🍈', 'desc' => 'Posisi kepala biasanya sudah di bawah bersiap lahir.'];
        return ['fruit' => 'Semangka Kecil', 'length' => '50 cm', 'weight' => '3.2 kg', 'emoji' => '🍉', 'desc' => 'Bayi Bunda sudah siap menyapa dunia!'];
    }
}
