<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        return view('mama.chat-ai.index');
    }

    public function chat(Request $request)
{
    $user = Auth::user();
    $kehamilan = $user->kehamilan;
    $userMessage = $request->input('message');
    // --- TAMBAHAN: Ambil riwayat chat dari frontend ---
    $history = $request->input('history', []);

    // Setup Konteks Kehamilan
    $konteks = "Nama user: {$user->name}. ";
    if ($kehamilan) {
        $hpht = Carbon::parse($kehamilan->hpht);
        $minggu = floor($hpht->diffInDays(Carbon::now()) / 7);
        $konteks .= "Mama sedang hamil minggu ke-{$minggu}. ";
    }

    // MEGA PROMPT BIAR GAK KAKU
    $systemInstruction = "Kamu adalah 'Mama AI', asisten pribadi sekaligus sahabat hangat bagi ibu hamil di aplikasi Mamacare.
    
    KEPRIBADIAN:
    - Ramah, empati, ceria, dan sangat suportif (gunakan panggilan 'Mama' atau 'Bun').
    - Bahasa santai, hangat, tapi tetap sopan (Bahasa Indonesia).
    - Gunakan emoji sesekali agar suasana chat hidup 😊✨.

    ATURAN JAWABAN:
    - Jika menjelaskan sesuatu yang panjang, WAJIB gunakan struktur yang rapi (Point-point/Bullet points atau List).
    - Berikan tips praktis yang bisa langsung dilakukan Mama di rumah.
    - Jangan terlalu kaku, anggap sedang mengobrol dengan teman baik.
    - Jika ada istilah medis, jelaskan dengan bahasa awam yang mudah dimengerti.

    ATURAN TEKNIS JAWABAN:
- DILARANG menggunakan karakter bintang (*) untuk alasan apapun.
- jangan menggunakan format text bold untuk alasan apapun.
- Gunakan tanda strip (-) atau angka untuk daftar (list).
- Tuliskan poin penting dengan HURUF KAPITAL tanpa tanda baca bintang.

    KONTEKS MAMA SAAT INI:
    {$konteks}";

    // --- TAMBAHAN: Susun payload 'contents' untuk Gemini agar ingat history ---
    $contents = [];

    // 1. Masukkan Instruksi Sistem
    $contents[] = [
        "role" => "user",
        "parts" => [["text" => "SISTEM: " . $systemInstruction]]
    ];
    $contents[] = [
        "role" => "model",
        "parts" => [["text" => "Siap Mama! Mama AI sudah siap membantu dengan ramah dan suportif. 😊"]]
    ];

    // 2. Masukkan riwayat percakapan sebelumnya (jika ada)
    foreach ($history as $chat) {
        $contents[] = [
            "role" => ($chat['role'] === 'user') ? 'user' : 'model',
            "parts" => [["text" => $chat['text']]]
        ];
    }

    // 3. Masukkan pesan terbaru Mama
    $contents[] = [
        "role" => "user",
        "parts" => [["text" => $userMessage]]
    ];

    // --- UPDATE: Panggil Gemini menggunakan array $contents ---
    $reply = $this->callGemini($contents);

    return response()->json([
        'reply' => $reply ?? "Duh, maaf ya Ma, sinyal Mama AI lagi agak bermasalah. Coba tanya lagi ya, Bun!"
    ]);
}

    private function callGemini($contents) // Nama variabel diubah jadi $contents agar lebih sesuai
{
    $apiKeys = explode(',', env('GEMINI_API_KEYS', ''));
    foreach ($apiKeys as $key) {
        $key = trim($key);
        if (empty($key)) continue;

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $key,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent", [
                // --- UPDATE: Langsung kirim $contents (karena strukturnya sudah array) ---
                "contents" => $contents
            ]);

            if ($response->successful()) {
                // Ambil teks jawaban dari struktur JSON Gemini
                return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }
        } catch (\Exception $e) { 
            continue; 
        }
    }
    return null;
}
}
