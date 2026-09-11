<?php

namespace App\Http\Controllers\Mama;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TanyaDokterController extends Controller
{
    // 1. HALAMAN DAFTAR DOKTER (index.blade.php)
    public function index()
{
    // Cukup ambil semua data dokter langsung
    $doctors = Doctor::all(); 

    return view('mama.tanya-dokter.index', compact('doctors'));
}

    // 2. HALAMAN RUANG CHAT (chat.blade.php)
    public function chat($id)
    {
        $activeDoctor = Doctor::findOrFail($id);
        

        return view('mama.tanya-dokter.chat', compact('activeDoctor'));
    }

    // 3. API: AMBIL PESAN (GET) - Tetap digunakan oleh Alpine.js
    public function getMessages($doctorId)
    {
        $userId = Auth::id();

        $conversation = Conversation::where('user_id', $userId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$conversation) {
            return response()->json([]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'text' => $msg->message,
                    'sender' => $msg->sender_type, 
                    'time' => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    // 4. API: KIRIM PESAN (POST) - Tetap digunakan oleh Alpine.js
    public function sendMessage(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'message' => 'required|string'
        ]);

        $userId = Auth::id();

        $conversation = Conversation::firstOrCreate(
            ['user_id' => $userId, 'doctor_id' => $request->doctor_id]
        );

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success', 
            'time' => $message->created_at->format('H:i')
        ]);
    }
}
