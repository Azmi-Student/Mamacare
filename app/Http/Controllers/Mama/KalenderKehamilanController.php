<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\KehamilanMama;
use Carbon\Carbon;

class KalenderKehamilanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $dataKehamilan = $user->kehamilan;
        $hphtInput = $request->query('hpht');

        if ($hphtInput) {
            $minDate = Carbon::now()->subDays(300)->format('Y-m-d');
            $maxDate = Carbon::now()->format('Y-m-d');
            $request->validate([
                'hpht' => ['required', 'date', 'after_or_equal:' . $minDate, 'before_or_equal:' . $maxDate],
            ]);
        }

        // Simpan HPHT jika baru pertama kali input
        if (!$dataKehamilan && $hphtInput) {
            $dataKehamilan = KehamilanMama::create([
                'user_id' => $user->id,
                'hpht' => $hphtInput
            ]);
        }

        $hphtFinal = $dataKehamilan ? $dataKehamilan->hpht : $hphtInput;

        if (!$hphtFinal) {
            return view('mama.kalender-kehamilan.index', ['data' => null]);
        }

        try {
            $stats = $this->hitungProgress($hphtFinal);
            $aiData = $this->getAiData($dataKehamilan, $stats['minggu']);

            return view('mama.kalender-kehamilan.index', [
                'data' => array_merge($stats, $aiData, ['hpht' => $hphtFinal])
            ]);
        } catch (\Exception $e) {
            if ($dataKehamilan) $dataKehamilan->delete();
            return redirect()->route('mama.kalender')->withErrors(['hpht' => 'Data kalender tidak valid dan telah direset.']);
        }
    }

    public function detail(Request $request)
    {
        $user = Auth::user();
        $dataKehamilan = $user->kehamilan;
        $hphtInput = $request->query('hpht');

        if ($hphtInput) {
            $minDate = Carbon::now()->subDays(300)->format('Y-m-d');
            $maxDate = Carbon::now()->format('Y-m-d');
            $request->validate([
                'hpht' => ['required', 'date', 'after_or_equal:' . $minDate, 'before_or_equal:' . $maxDate],
            ]);
        }

        $hpht = $dataKehamilan ? $dataKehamilan->hpht : $hphtInput;

        if (!$hpht) return redirect()->route('mama.kalender');

        try {
            $stats = $this->hitungProgress($hpht);
            $aiData = $this->getAiData($dataKehamilan, $stats['minggu']);

            return view('mama.kalender-kehamilan.detail', [
                'data' => array_merge($stats, $aiData, ['hpht' => $hpht])
            ]);
        } catch (\Exception $e) {
            if ($dataKehamilan) $dataKehamilan->delete();
            return redirect()->route('mama.kalender')->withErrors(['hpht' => 'Data kalender tidak valid dan telah direset.']);
        }
    }

    private function hitungProgress($hpht)
    {
        $tanggal_hpht = Carbon::parse($hpht);
        $hari_ini = Carbon::now();
        $total_hari = $tanggal_hpht->diffInDays($hari_ini);
        $minggu = floor($total_hari / 7);
        $hari = $total_hari % 7;
        $hpl = Carbon::parse($hpht)->addDays(280);
        $sisa_hari = (int) $hari_ini->diffInDays($hpl, false);
        $trimester = ($minggu >= 28) ? 3 : (($minggu >= 13) ? 2 : 1);

        return [
            'minggu' => $minggu,
            'hari' => $hari,
            'hpl' => $hpl->translatedFormat('d F Y'),
            'sisa_hari' => max(0, $sisa_hari),
            'trimester' => $trimester,
            'persen' => min(100, round(($total_hari / 280) * 100))
        ];
    }

    /**
     * Memanggil AI Data (Cache atau fetch baru dari Gemini)
     */
    private function getAiData($dataKehamilan, $minggu)
    {
        $hariIni = Carbon::now()->format('Y-m-d');

        if ($dataKehamilan && $dataKehamilan->ai_data) {
            $cachedData = json_decode($dataKehamilan->ai_data, true);
            if (isset($cachedData['minggu_cache']) && $cachedData['minggu_cache'] == $minggu) {
                
                // LOGIKA AUTO-RESET HARIAN
                $lastChecklistDate = $cachedData['last_checklist_date'] ?? null;
                
                if ($lastChecklistDate !== $hariIni) {
                    // Jika beda hari, kosongkan centang
                    $cachedData['checklist_status'] = [];
                    $cachedData['last_checklist_date'] = $hariIni;
                    
                    // Update ke database tanpa perlu call API Gemini lagi
                    $dataKehamilan->update(['ai_data' => json_encode($cachedData)]);
                }

                return $cachedData;
            }
        }

        $aiResponse = $this->generateMegaAI($minggu);
        
        if ($aiResponse) {
            $dataUntukSimpan = array_merge([
                'minggu_cache' => $minggu,
                'last_checklist_date' => $hariIni,
                'checklist_status' => []
            ], $aiResponse);

            if ($dataKehamilan) {
                $dataKehamilan->update(['ai_data' => json_encode($dataUntukSimpan)]);
            }

            return $dataUntukSimpan;
        }

        return array_merge([
            'minggu_cache' => $minggu,
            'last_checklist_date' => $hariIni,
            'checklist_status' => []
        ], $this->fallbackData());
    }

    /**
     * MEGA PROMPT: Sekali tarik dapat semua data
     */
    private function generateMegaAI($minggu)
{
    $prompt = "Bertindaklah sebagai pakar kehamilan Mamacare. Berikan laporan komprehensif minggu ke-{$minggu} untuk Ibu hamil dalam Bahasa Indonesia.
    
    ATURAN WAJIB:
    1. Output HARUS JSON murni tanpa teks pembuka/penutup dan tanpa markdown (```json).
    2. Semua poin list harus menyertakan contoh konkret di dalam kurung ().
    3. Isi list minimal 3 poin per kategori agar data tidak kosong.
    
    STRUKTUR JSON:
    {
        \"ukuran\": \"seukuran buah/benda (misal: Jeruk Nipis)\",
        \"tips\": \"1 tips kesehatan mental/fisik paling relevan minggu ini\",
        \"detail\": \"perkembangan organ utama janin saat ini (misal: jantung mulai berdetak)\",
        \"harian\": [\"aktivitas rutin (contoh: jalan pagi 15 menit)\", \"...\"],
        \"mingguan\": [\"checklist minggu ini (contoh: cek berat badan)\", \"...\"],
        \"bulanan\": [\"persiapan besar (contoh: booking jadwal USG)\", \"...\"],
        \"rekomendasi\": [\"nutrisi wajib (contoh: asam folat dari alpukat)\", \"...\"],
        \"hindari\": [\"pantangan keras (contoh: daging mentah/sushi)\", \"...\"],
        \"perlengkapan_mama\": \"1 kalimat saran perlengkapan hamil (misal: Beli bra menyusui)\",
        \"persiapan_kamar\": \"1 kalimat saran persiapan area bayi (misal: Pastikan sirkulasi udara kamar baik)\"
    }";

    return $this->callGemini($prompt);
}

    /**
     * Data Cadangan jika AI Error
     */
    private function fallbackData()
    {
        return [
            'ukuran' => 'Sedang tumbuh',
            'tips' => 'Jaga kesehatan dan istirahat cukup ya Ma!',
            'detail' => 'Janin sedang berkembang dengan baik minggu ini.',
            'harian' => ['Minum air putih 2-3 Liter', 'Makan buah/sayur'],
            'mingguan' => ['Olahraga ringan pagi'],
            'bulanan' => ['Cek jadwal kontrol dokter'],
            'rekomendasi' => ['Asam folat', 'Protein'],
            'hindari' => ['Stres berlebih', 'Angkat beban berat'],
            'perlengkapan_mama' => 'Pakaian Longgar, Vitamin Prenatal, Krim Stretchmark.',
            'persiapan_kamar' => 'Cek Area Tidur, Atur Ventilasi, Siapkan Musik Relaksasi.'
        ];
    }

    private function callGemini($prompt)
    {
        $apiKeys = explode(',', env('GEMINI_API_KEYS', ''));
        foreach ($apiKeys as $key) {
            $key = trim($key);
            if (empty($key)) continue;

            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $key,
                    'Content-Type' => 'application/json',
                ])->timeout(20)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent", [
                    "contents" => [["parts" => [["text" => $prompt]]]]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    
                    if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
                        $parsed = json_decode($matches[0], true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            return $parsed;
                        }
                    }
                }
            } catch (\Exception $e) { continue; }
        }
        return null;
    }

    public function reset() 
    { 
        $user = Auth::user();
        if ($user->kehamilan) $user->kehamilan->delete();
        return redirect()->route('mama.kalender'); 
    }
    /**
     * Update status checklist harian ke database (JSON)
     */
    public function updateChecklist(Request $request)
    {
        $user = Auth::user();
        $dataKehamilan = $user->kehamilan;

        if (!$dataKehamilan || !$dataKehamilan->ai_data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        try {
            // 1. Decode data AI yang sudah ada
            $aiData = json_decode($dataKehamilan->ai_data, true);
            
            // 2. Ambil input dari Fetch/AJAX
            $index = $request->input('index');
            $isChecked = $request->input('checked');

            // 3. Siapkan atau update key 'checklist_status'
            // Kita simpan dalam bentuk array asosiatif: [index => true/false]
            $currentStatus = $aiData['checklist_status'] ?? [];
            $currentStatus[$index] = $isChecked;
            
            $aiData['checklist_status'] = $currentStatus;
            $aiData['last_checklist_date'] = Carbon::now()->format('Y-m-d'); // Catat tanggal update terakhir

            // 4. Simpan kembali ke database dalam bentuk JSON
            $dataKehamilan->update([
                'ai_data' => json_encode($aiData)
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
