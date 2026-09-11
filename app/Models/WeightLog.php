<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    protected $fillable = ['user_id', 'weight', 'week_number', 'date', 'notes'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
