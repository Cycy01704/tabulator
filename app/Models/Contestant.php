<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contestant extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'number', 'age', 'address', 'gender', 'dob', 'occupation', 'contact_number', 'email', 'hobbies', 'motto', 'description', 'image_path', 'event_id'];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
