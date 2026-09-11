<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'category', 
        'excerpt', 'content', 'image', 'status', 'views'
    ];

    // Relasi ke User (Dokter)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Biar nanti url-nya cantik (pake slug)
    public function getRouteKeyName()
    {
        return 'slug';
    }
}