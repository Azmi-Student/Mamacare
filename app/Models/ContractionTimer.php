<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractionTimer extends Model
{
    protected $fillable = ['user_id', 'start_time', 'end_time', 'duration_seconds', 'interval_seconds', 'date', 'intensity'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
