<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FoodScannerController extends Controller
{
    public function index()
    {
        return view('mama.food-scanner.index');
    }

    public function scan(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:5120', // Max 5MB
            ]);

            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
                return response()->json(['success' => false, 'message' => 'Upload gagal atau file corrupt.'], 400);
            }

            $image = $request->file('image');
            $base64Image = base64_encode(file_get_contents($image->path()));
            $mimeType = $image->getMimeType();

            // 1. Setup Konteks Kehamilan (seperti di ChatController)
            $user = Auth::user();
            $kehamilan = $user->kehamilan;
            $konteks = "Nama user: {$user->name}. ";
            if ($kehamilan) {
                $hpht = Carbon::parse($kehamilan->hpht);
                $minggu = floor($hpht->diffInDays(Carbon::now()) / 7);
                $konteks .= "Mama sedang hamil minggu ke-{$minggu}. ";
            }

            // 2. Prompt Spesifik AI Gizi
            $systemInstruction = "Kamu adalah ahli gizi ibu hamil. 
KONTEKS MAMA SAAT INI: {$konteks}
TUGAS: Analisis gambar makanan ini. Berikan jawaban dalam format HTML murni tanpa tag ```html. 
Gunakan bahasa santai, hangat ala 'Mama AI' dengan emoji, dan sebut nama usernya.
Struktur Wajib:
1. <h3>[Nama Makanan Tebakanmu]</h3>
2. <p>Sapa Mama dan berikan penjelasan singkat.</p>
3. <h4>Kandungan Gizi:</h4><ul><li>Kalori: ...</li><li>Protein: ...</li><li>Asam Folat / dll: ...</li></ul>
4. <h4>Peringatan / Saran:</h4><p>Berikan peringatan keamanan pangan untuk usia kehamilan ini (jika ada).</p>";

            // 3. Susun payload menggunakan pola yang sama dengan ChatController
            $contents = [];
            
            // Masukkan instruksi sistem
            $contents[] = [
                "role" => "user",
                "parts" => [["text" => "SISTEM: " . $systemInstruction]]
            ];
            
            $contents[] = [
                "role" => "model",
                "parts" => [["text" => "Siap! Mama AI siap menganalisis makanan Bunda."]]
            ];

            // Masukkan gambar dan perintah
            $contents[] = [
                "role" => "user",
                "parts" => [
                    ["text" => "Tolong analisis makanan di foto ini ya!"],
                    [
                        "inlineData" => [
                            "mimeType" => $mimeType,
                            "data" => $base64Image
                        ]
                    ]
                ]
            ];

            $apiKeys = explode(',', env('GEMINI_API_KEYS', ''));
            foreach ($apiKeys as $key) {
                $key = trim($key);
                if (empty($key)) continue;

                try {
                    $response = Http::withHeaders([
                        'x-goog-api-key' => $key,
                        'Content-Type' => 'application/json',
                    ])->timeout(45)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent", [
                        "contents" => $contents
                    ]);

                if ($response->successful()) {
                    $htmlResult = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if($htmlResult) {
                         // Bersihkan kalau AI masih membandel pakai backtick
                         $htmlResult = str_replace(['```html', '```'], '', $htmlResult);
                         return response()->json(['success' => true, 'result' => trim($htmlResult)]);
                    }
                } else {
                    Log::error("Gemini API Error: " . $response->body());
                }
            } catch (\Exception $e) {
                continue;
            }
        }

            return response()->json(['success' => false, 'message' => 'Duh, sinyal Mama AI lagi sibuk nih. Coba foto lagi ya Bun!'], 500);
        } catch (\Exception $e) {
            Log::error("FoodScanner Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
