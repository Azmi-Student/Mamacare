<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MamaAiController extends Controller
{
    public function chat(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'message' => 'required|string',
        ]);

        // Ambil pesan dari pengguna
        $userMessage = $request->input('message');
        
        // Ambil API Key dari .env
        $apiKey = env('GEMINI_API_KEY');

        // Validasi apakah API Key ada
        if (!$apiKey) {
            return response()->json(['reply' => 'API Key tidak ditemukan.'], 500);
        }

        // 2. Tentukan Prompt Persona untuk AI
        $systemInstruction = "Kamu adalah 'Mama.AI', asisten virtual cerdas untuk aplikasi 'MamaCare'. 
        Tugasmu adalah menjawab pertanyaan seputar kehamilan, kesehatan ibu dan anak, serta nutrisi dengan ramah, empatik, dan suportif. 
        Panggil pengguna dengan sebutan 'Bunda'. Gunakan bahasa Indonesia yang sopan dan mudah dimengerti. 
        Jika pertanyaan di luar topik kesehatan/kehamilan, jawab dengan sopan bahwa kamu hanya fokus pada kesehatan ibu dan anak.";

        try {
            // 3. Kirim Request ke Google Gemini API
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";  // Pastikan model dan versi sudah benar
            Log::info('API Request Sent', ['url' => $url]);

            // Melakukan POST request ke Google Gemini API
            $response = Http::timeout(3000) // Set timeout to 30 seconds
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemInstruction . "\n\nUser: " . $userMessage . "\nMama.AI:"]
                            ]
                        ]
                    ]
                ]);

            // 4. Cek status response API
            if ($response->successful()) {
                // Menangani data respons yang sukses
                $data = $response->json();
                Log::info('API Response Data', ['data' => $data]); // Log data respons untuk debugging

                // Periksa apakah respons valid dan ada teks dalam bagian 'candidates'
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                } else {
                    // Jika tidak ada teks yang valid
                    $reply = 'Maaf Bunda, saya tidak mengerti.';
                }
            } else {
                // Jika API gagal, log error dan beri respons default
                $reply = 'Maaf Bunda, Mama.AI sedang gangguan sinyal.';
                Log::error('API Request failed', ['response' => $response->body()]);
            }

        } catch (\Exception $e) {
            // Tangani exception jika API tidak dapat diakses atau error lainnya
            $reply = 'Maaf Bunda, terjadi kesalahan pada sistem AI kami.';
            Log::error('API Request Error', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // 5. Kembalikan hasil respons ke frontend
        return response()->json(['reply' => $reply]);
    }
}
