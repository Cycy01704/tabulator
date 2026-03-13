<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criterion extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'weight', 'event_id'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
