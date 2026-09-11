<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehamilanMama extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = ['user_id', 'hpht','ai_data'];

    // Relasi: Data kehamilan ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}