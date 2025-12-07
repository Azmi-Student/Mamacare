<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',      // ID Pasien
        'dokter_id',    // ID Dokter
        'sender_id',    // ID Pengirim (Bisa Pasien atau Dokter)
        'message',      // Isi Pesan
        'is_read'       // Status Terbaca (Opsional)
    ];

    // Relasi ke User (Pasien)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User (Dokter)
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
}