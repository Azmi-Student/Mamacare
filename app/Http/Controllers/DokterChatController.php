<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DokterChatController extends Controller
{
    /**
     * 1. HALAMAN UTAMA CHAT DOKTER
     * Menampilkan view chat dan daftar pasien yang pernah menghubungi dokter ini.
     */
    public function index()
    {
        $dokterId = Auth::id();

        // Cari ID semua pasien yang pernah chat dengan dokter ini
        // Kita ambil kolom 'user_id' dari tabel chats dimana dokter_id = login sekarang
        $pasienIds = Chat::where('dokter_id', $dokterId)
                        ->pluck('user_id') // Ambil ID-nya saja
                        ->unique();        // Pastikan unik (tidak duplikat)

        // Ambil data detail User (Pasien) berdasarkan ID yang ditemukan
        $pasiens = User::whereIn('id', $pasienIds)->get();

        // Kembalikan ke view 'dokter.chat'
        return view('dokter.chat', compact('pasiens'));
    }

    /**
     * 2. API: AMBIL RIWAYAT CHAT (GET)
     * Dipanggil via AJAX saat dokter klik salah satu pasien.
     */
    public function getMessages($pasienId)
    {
        $dokterId = Auth::id();

        // Ambil chat antara Pasien terpilih dan Dokter yang login
        $chats = Chat::where(function($q) use ($pasienId, $dokterId) {
            $q->where('user_id', $pasienId)
              ->where('dokter_id', $dokterId);
        })
        ->orderBy('created_at', 'asc') // Urutkan dari terlama ke terbaru
        ->get();

        return response()->json($chats);
    }

    /**
     * 3. API: KIRIM PESAN BALASAN (POST)
     * Dipanggil via AJAX saat dokter mengetik pesan dan tekan enter/kirim.
     */
    public function store(Request $request, $pasienId)
    {
        // Validasi input
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $dokterId = Auth::id();

        // Simpan pesan baru
        $chat = Chat::create([
            'user_id'   => $pasienId,    // Lawan bicaranya (Pasien)
            'dokter_id' => $dokterId,    // Saya sendiri (Dokter)
            'sender_id' => $dokterId,    // Pengirimnya adalah SAYA (Dokter)
            'message'   => $request->message,
            'is_read'   => false
        ]);

        return response()->json([
            'status' => 'success',
            'chat' => $chat
        ]);
    }
}