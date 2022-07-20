<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    //
    protected $fillable = ['creator_id', 'event_id', 'registered'];

    public function events()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}