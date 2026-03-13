<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Archive extends Model
{
    /** @use HasFactory<\Database\Factories\ArchiveFactory> */
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_date',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'event_date' => 'date',
    ];
}
