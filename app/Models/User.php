<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne; // Tambahkan import ini

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke data Kalender Kehamilan Mama
     * Satu user hanya memiliki satu data HPHT
     */
    public function kehamilan(): HasOne
    {
        return $this->hasOne(KehamilanMama::class);
    }
    // Relasi: User bisa punya profil Dokter (kalau dia dokter)
    public function doctor() {
        return $this->hasOne(Doctor::class);
    }

    public function communityPosts() {
        return $this->hasMany(CommunityPost::class);
    }
    
    public function communityLikes() {
        return $this->hasMany(CommunityLike::class);
    }
    
    public function communityComments() {
        return $this->hasMany(CommunityComment::class);
    }
}