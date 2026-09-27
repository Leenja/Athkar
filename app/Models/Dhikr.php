<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dhikr extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'text_ar',
        'repeat_count',
        'audio_url',
        'source',
        'license',
        'reviewed_by',
        'reviewed_at',
        'order',
    ];
}
