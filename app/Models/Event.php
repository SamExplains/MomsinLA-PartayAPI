<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'start_time', 'end_time', 'first_image', 'second_image', 'address', 'city', 'zip', 'public_private', 'on_off_line', 'check_in', 'capacity', 'additional_items', 'title', 'url', 'email', 'phone', 'qrcode', 'creator_id', 'event_key', 'private_authentication_code'];

}