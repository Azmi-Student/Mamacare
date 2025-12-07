<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User; // Jangan lupa import User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // 1. KIRIM PESAN
    public function store(Request $request, $dokterId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();

        // Simpan pesan
        $chat = Chat::create([
            'user_id' => $userId,      // Pasiennya adalah user yang login
            'dokter_id' => $dokterId,  // Dokternya dari URL
            'sender_id' => $userId,    // Pengirimnya adalah user yang login
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'chat' => $chat
        ]);
    }

    // 2. AMBIL RIWAYAT CHAT (GET)
    public function getMessages($dokterId)
    {
        $userId = Auth::id();

        // Ambil chat antara User Login (Pasien) dan Dokter yang dipilih
        $chats = Chat::where(function($q) use ($userId, $dokterId) {
            $q->where('user_id', $userId)
              ->where('dokter_id', $dokterId);
        })
        ->orderBy('created_at', 'asc') // Urutkan dari terlama ke terbaru
        ->get();

        return response()->json($chats);
    }
}