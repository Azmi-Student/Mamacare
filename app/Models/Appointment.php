<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    // Kita definisikan field apa saja yang boleh diisi
    protected $fillable = [
        'user_id',
        'doctor_id',
        'date',
        'time',
        'notes',        // Input Pasien
        'diagnosis',    // Input Dokter
        'prescription', // Input Dokter
        'image',        // Input Dokter
        'status',
    ];

    // Relasi ke User (Pasien)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Dokter
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
}