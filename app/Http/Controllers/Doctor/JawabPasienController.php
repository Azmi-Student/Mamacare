<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class JawabPasienController extends Controller
{
    /**
     * Halaman Utama Inbox Dokter (List Pasien)
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('profile.edit')->with('error', 'Profil dokter belum diset. Harap lengkapi profil.');
        }

        // Ambil percakapan milik dokter ini
        $conversations = Conversation::where('doctor_id', $doctor->id)
            ->with(['user', 'messages' => function($query) {
                $query->latest()->first(); // Ambil pesan terakhir untuk preview
            }])
            ->orderByDesc('updated_at') // Chat terbaru paling atas
            ->get();

        // Format data untuk Frontend (Alpine.js)
        $patients = $conversations->map(function ($chat) {
            $lastMsg = $chat->messages->first();
            
            return [
                'id'        => $chat->user->id, // ID Pasien (User)
                'name'      => $chat->user->name,
                'avatar'    => substr($chat->user->name, 0, 1), // Inisial Nama
                'last_msg'  => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->message, 30) : 'Belum ada pesan',
                'time'      => $chat->updated_at->diffForHumans(), // "1 menit yang lalu"
                // Hitung pesan dari USER yang belum dibaca
                'unread'    => $chat->messages->where('is_read', false)->where('sender_type', 'user')->count(),
            ];
        });

        return view('dokter.jawab-pasien.index', compact('patients'));
    }

    /**
     * API: Ambil Detail Pesan (GET)
     */
    public function getMessages($userId)
    {
        $doctor = Auth::user()->doctor;

        $conversation = Conversation::where('doctor_id', $doctor->id)
            ->where('user_id', $userId)
            ->first();

        if (!$conversation) {
            return response()->json([]);
        }

        // Tandai pesan dari user sebagai "Sudah Dibaca"
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Return semua pesan
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'text'   => $msg->message,
                    'sender' => $msg->sender_type, // 'doctor' atau 'user'
                    'time'   => $msg->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }

    /**
     * API: Kirim Balasan (POST)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required', // ID Pasien tujuan
            'message' => 'required|string'
        ]);

        $doctor = Auth::user()->doctor;

        $conversation = Conversation::where('doctor_id', $doctor->id)
            ->where('user_id', $request->user_id)
            ->firstOrFail();

        // Simpan Pesan (Sender = Doctor)
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'doctor',
            'message'         => $request->message,
            'is_read'         => false,
        ]);

        // Update timestamp conversation agar naik ke atas list
        $conversation->touch();

        return response()->json(['status' => 'success', 'time' => $message->created_at->format('H:i')]);
    }
}