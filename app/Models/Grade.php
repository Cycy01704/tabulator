<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;
    protected $fillable = ['criterion_id', 'label', 'score'];

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
