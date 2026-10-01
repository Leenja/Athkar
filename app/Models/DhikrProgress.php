<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DhikrProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dhikr_id',
        'current_count',
        'progress_date'
    ];
}
