<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // Sesuaikan dengan migration + phone_number
    protected $fillable = [
        'user_id',      // Relasi ke tabel users (akun login dokter)
        'name',
        'specialist',
        'phone_number', // Kolom tambahan untuk WA
        'description',
        'experience',
        'image',
    ];

    // --- RELASI (RELATIONSHIPS) ---

    // 1. Relasi ke User (Akun Login)
    // Karena di table doctors ada 'user_id'
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Relasi ke Appointment (Sesuai referensi kamu)
    // Karena satu dokter bisa menangani banyak appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // --- ACCESSOR / HELPER ---

    // Logic ubah 08xx jadi 628xx biar bisa diklik di WA
    // Cara panggil di Blade: $doctor->whatsapp_url
    public function getWhatsappUrlAttribute()
    {
        // Ambil nomor dari database
        $number = $this->phone_number;

        // Hapus karakter selain angka (misal ada spasi atau strip)
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali '0', ganti dengan '62'
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        return "https://wa.me/{$number}";
    }
    
    // Opsional: Accessor untuk Gambar
    // Cara panggil: $doctor->image_url
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        // Gambar default kalau dokter belum upload foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF3EA5&color=fff';
    }
    // Ambil inisial nama (Menghilangkan Dr. atau dr.)
    public function getAvatarAttribute()
    {
        if ($this->user && $this->user->avatar) {
            return $this->user->avatar;
        }
        return $this->image;
    }
}