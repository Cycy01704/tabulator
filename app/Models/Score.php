<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use SoftDeletes;
    protected $fillable = ['judge_id', 'contestant_id', 'criterion_id', 'score'];

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function contestant()
    {
        return $this->belongsTo(Contestant::class);
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
